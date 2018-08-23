$(function() {
    function ViewModel(onViewModelReadyCB) {
        var that = this;
        /*
         * Public properties
         */
        $.extend(that, {
            spinnerShowCount: ko.observable(0),
            persons: ko.observableArray([]),
            isEditingList: ko.observable(false),
            displayedItemId: ko.observable(null),
            itemDetails: ko.observable(_buildEmptyPerson()),
            isEditingItem: ko.observable(false),
            isFormDirty: ko.observable(false),
        });
        /*
         * Computed observables
         */
        $.extend(that, {
            mustShowSpinner: ko.computed(function() {
                return that.spinnerShowCount() > 0;
            }, that),
            isIndexAction: ko.computed(function() {
                return that.displayedItemId() == null;
            }, that),
            isCreateAction: ko.computed(function() {
                return that.displayedItemId() == 0;
            }, that),
            isReadAction: ko.computed(function() {
                return that.displayedItemId()>0 && !that.isEditingItem();
            }, that),
            isUpdateAction: ko.computed(function() {
                return that.displayedItemId()>0 && that.isEditingItem();
            }, that),
        });
        $.extend(that, {
            mustShowIndex: ko.computed(function() {
                return that.isIndexAction();
            }, that),
            mustShowForm: ko.computed(function() {
                return that.isCreateAction() || that.isReadAction() || that.isUpdateAction();
            }, that),
            mustShowEditItemButton: ko.computed(function() {
                return that.isReadAction() || that.isUpdateAction();
            }, that),
            mustShowSubmitCancelButton: ko.computed(function() {
                return that.isCreateAction() || that.isUpdateAction();
            }, that),
        });
        /*
         * Public methods
         */
        $.extend(that, {
            onRefreshButtonClicked: function() {
                _initiateRefreshIndexAction();
            },
            onNewItemButtonClicked: function() {
                _showCreateForm();
            },
            onEditListButtonClicked: function() {
                if(that.isEditingList()) {
                    _exitEditListMode();
                } else {
                    _enterEditListMode();
                }
            },
            onDeleteItemButtonClicked: function(person, event) {
                event.stopPropagation();
                _initiateDeleteAction(person);
            },
            onItemClicked: function(person) {
                var fullName = person.lastname + ', ' + person.firstname;
                if(!that.isEditingList()) {
                    _showReadForm(person);
                } else if(that.isEditingList() &&
                    confirm("Confirmez la suppression du contact '" + fullName + "' ?")) {
                    _initiateDeleteAction(person);
                }
            },
            onEditItemButtonClicked: function() {
                if(that.isEditingItem()) {
                    if(_confirmEditingCompletion()) {
                        if(that.isFormDirty()) {
                            _triggerFormSubmit();
                        } else {
                            _exitItemEditMode();
                        }
                    }
                } else {
                    _enterItemEditMode();
                }
            },
            onFieldValueChange: function() {
              that.isFormDirty(true);
            },
            onCancelButtonClicked: function() {
                if(_confirmDataLoss()) {
                    if(that.isCreateAction()) {
                        _dismissCreateReadUpdateForm();
                    } else {
                        _exitItemEditMode();
                    }
                }
            },
            onBackButtonClicked: function() {
                if(_confirmDataLoss()) {
                    _dismissCreateReadUpdateForm();
                }
            },
        });

        /*
         * Private methods / helpers
         */
        function _buildEmptyPerson() {
            return {
                id: "",
                firstname: "",
                lastname: "",
                occupation: { id: "", displayname: null },
                retired: false,
                telephone: "",
                email: "",
                comment: "",
                createtimestamp: ""
            };
        }

        function _manageSpinner(deferred) {
            that.spinnerShowCount(that.spinnerShowCount() + 1);;
            return deferred.always(function() { that.spinnerShowCount(that.spinnerShowCount() - 1); });
        }

        function _initiateRefreshIndexAction() {
            return _manageSpinner($.get("index.php/persons", function(data) {
                that.persons.removeAll();
                $.each(data, function(_, item) {
                    that.persons.push(item);
                });
            }));
        }

        function _confirmDataLoss() {
            return that.isReadAction() ||
                !that.isFormDirty() ||
                confirm('Les modifications seront perdues. Souhaitez-vous continuer ?');
        }

        function _dismissCreateReadUpdateForm() {
            _showIndex();
        }

        function _confirmEditingCompletion() {
            return !that.isFormDirty() ||
                confirm('Enregistrer les modifications ?');
        }

        function _showIndex() {
            that.displayedItemId(null);
        }

        function _enterEditListMode() {
            that.isEditingList(true);
        }

        function _exitEditListMode() {
            that.isEditingList(false);
        }

        function _showCreateForm() {
            that.itemDetails(_buildEmptyPerson());
            that.displayedItemId(0);
            _enterItemEditMode();
        }

        function _showReadForm(person) {
            var personClone = $.extend(true, {}, person);
            that.itemDetails(personClone);
            that.displayedItemId(personClone.id);
            _exitItemEditMode();
        }

        function _enterItemEditMode() {
            $("#person_form form :input").removeAttr("disabled");
            that.isEditingItem(true);
            that.isFormDirty(false);
        }

        function _exitItemEditMode() {
            $("#person_form form :input").attr("disabled", "1");
            that.isEditingItem(false);
            that.isFormDirty(false);
        }

        function _initiateDeleteAction(person) {
            _deletePerson(person.id)
                .done(function() {
                    that.persons.remove(person);
                })
                .fail(function(e) {
                    alert("Suppression impossible (" + e.statusText + ")");
                })
            ;
        }

        function _deletePerson(personId) {
            return _manageSpinner($.ajax({
                url: "index.php/persons/" + personId,
                type: 'DELETE'
            }));
        }

        function _triggerFormSubmit() {
            $("#person_form form").submit();
        }

        function _setFormSubmitHandler() {
            var personForm = $("#person_form form");
            personForm.submit(function(event) {
                event.preventDefault();
                var url = '/index.php/persons' +
                    (that.isUpdateAction() ? '/' + that.itemDetails().id : '');
                var method = that.isCreateAction() ? 'POST' : 'PUT';
                var formData = personForm.serialize();
                _manageSpinner($.ajax({
                    url: url,
                    type: method,
                    data: formData,
                }))
                .done(function(response) {
                    if(response.status != 'success') {
                        alert("Sauvegarde impossible :\n" +
                            response.message || response.status
                        );
                    } else if(that.isCreateAction()) {
                        that.persons.push(response.data);
                        _dismissCreateReadUpdateForm();
                    } else {
                        var updatedId = that.itemDetails().id;
                        that.persons.remove(function(item) {
                            return item.id == updatedId;
                        });
                        that.persons.push(response.data);
                        _exitItemEditMode();
                    }
                })
                .fail(function(e) {
                    alert("Sauvegarde impossible (" + e.statusText + ")");
                })
            });
        }

        function _onStart() {
            _setFormSubmitHandler();
            _showIndex();
            _initiateRefreshIndexAction();
        }

        onViewModelReadyCB(that);
        _onStart();

        return that;
    }

    new ViewModel(function(viewModel) {
        ko.applyBindings(viewModel);
        $("#person_index, #person_form").removeClass("invisible");
    });

});

