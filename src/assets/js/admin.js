jQuery(function ($) {

    $('#wpcf7-admin-form-element #conditional-recipient').each(function () {

        $.get(WPCF7CR.pluginsUrl + "assets/templates/admin.ejs", function (template) {
            function generateForm(element, data) {
                let el = $(element);
                el.html(ejs.render(template, data));
            }

            function dumpData(element) {
                let el = $(element);
                let newData = {"recipients": []};
                el.find('.conditional-recipient').each(function () {
                    let emails = [];

                    $(this).find('.conditional-recipient-email-field input').val(function (index, value) {
                        emails.push(value);
                    });

                    let newRecipient = {"emails": emails, "or_structures": []};
                    $(this).find('.conditional-recipient-or').each(function () {
                        let newOrStructure = [];
                        $(this).find('.conditional-recipient-and').each(function () {
                            let field = $(this).find('.conditional-recipient-and-field-form input').val();
                            let operator = $(this).find('.conditional-recipient-and-operator-form select').val();
                            let value = $(this).find('.conditional-recipient-and-value-form input').val();
                            let newAndStructure = {'field': field, 'operator': operator, 'value': value};
                            newOrStructure.push(newAndStructure);
                        });
                        newRecipient['or_structures'].push(newOrStructure);
                    });
                    newData['recipients'].push(newRecipient);
                })
                return newData;
            }

            $(document).on('click', '.conditional-recipient-add-email', function () {
                let list = $(this).parents('.conditional-recipient-list');
                let data = dumpData(list);

                let recipientIndex = $(this).parents('.conditional-recipient').index();

                data['recipients'][recipientIndex]['emails'].push('');

                generateForm(list, data);
            });

            $(document).on('click', '.conditional-recipient-remove-email', function () {
                let list = $(this).parents('.conditional-recipient-list');
                let data = dumpData(list);

                let recipientIndex = $(this).parents('.conditional-recipient').index();
                let emailIndex = $(this).parents('.conditional-recipient-email-address').index();

                if(emailIndex) {
                    data['recipients'][recipientIndex]['emails'].splice(emailIndex, 1);
                }

                generateForm(list, data);
            });

            $(document).on('click', '.conditional-recipient-and-del-button', function () {
                let list = $(this).parents('.conditional-recipient-list');
                let data = dumpData(list);

                let andIndex = $(this).parents('.conditional-recipient-and').index();
                let orIndex = $(this).parents('.conditional-recipient-or').index();
                let recipientIndex = $(this).parents('.conditional-recipient').index();

                data['recipients'][recipientIndex]['or_structures'][orIndex].splice(andIndex - (orIndex > 0 ? 1 : 0), 1);

                if (!data['recipients'][recipientIndex]['or_structures'][orIndex].length) {
                    data['recipients'][recipientIndex]['or_structures'].splice(orIndex, 1);
                }

                if (!data['recipients'][recipientIndex]['or_structures'].length && recipientIndex > 0) {
                    data['recipients'].splice(recipientIndex, 1);
                }

                generateForm(list, data);
            });

            $(document).on('click', '.conditional-recipient-or-add-button', function () {
                let list = $(this).parents('.conditional-recipient-list');
                let data = dumpData(list);

                let newOrStructure = [
                    [
                        {
                            "field": "",
                            "operator": "equals",
                            "value": ""
                        }
                    ]
                ];

                let recipientIndex = $(this).parents('.conditional-recipient').index();

                data['recipients'][recipientIndex]['or_structures'].push(newOrStructure);

                generateForm(list, data);
            });

            $(document).on('click', '.conditional-recipient-and-add-button', function () {
                let list = $(this).parents('.conditional-recipient-list');
                let data = dumpData(list);

                let andIndex = $(this).parents('.conditional-recipient-and').index();
                let orIndex = $(this).parents('.conditional-recipient-or').index();
                let recipientIndex = $(this).parents('.conditional-recipient').index();

                let newAnd = {
                    "field": "",
                    "operator": "equals",
                    "value": ""
                };

                data['recipients'][recipientIndex]['or_structures'][orIndex].splice(andIndex + 1, 0, newAnd);
                generateForm(list, data);
            });

            $(document).on('click', '.conditional-recipient-add-button', function () {
                let list = $(this).parents('.conditional-recipient-list');
                let data = dumpData(list);

                let newRecipient = {
                    "email": "",
                    "or_structures": [
                        [
                            {
                                "field": "",
                                "operator": "equals",
                                "value": ""
                            }
                        ]
                    ]
                };

                data['recipients'].push(newRecipient);
                generateForm(list, data);
            });

            $.ajax({
                url: WPCF7CR.ajax,
                type: "POST",
                data: {
                    'action': 'wpcf7cr_settings',
                    'post_id': $("#post_ID").val()
                }
            }).done(function(response) {
                $('#wpcf7-admin-form-element #conditional-recipient .conditional-recipient-list').each(function () {
                    generateForm(this, response.data);
                });
            });


        });

    });
});
