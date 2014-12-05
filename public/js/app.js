(function($){

    var ApiRoot = '/api/v1';

    // ------- Models ------

    var ActivityItem = Backbone.Model.extend({

        urlRoot: ApiRoot + '/activities',

        initialize: function() {
            this.on('destroy', function(){
                $("#activity_list").removeClass('loading');
            });
        },
        change: function(){
            this.amount = this.escape('amount')
            this.amount.toFixed(2);
        },
        parse: function(response){
            this.set({'id': response.id, 'amount': response.amount, 'description': response.description});
        }

    });

    var ActivityItemCollection = Backbone.Collection.extend({

        model: ActivityItem,
        url: ApiRoot + '/activities',
        initialize: function() {
            this.on('request', function(model, xhr){
                $("#activity_list").addClass('loading');
            });
            this.on('sync error', function(){
                $("#activity_list").removeClass('loading');
            });
        },
        parse:function(response){
            return response;
        }

    });

    var activityList = new ActivityItemCollection();


    // ----- Views ------

    var ActivityView = Backbone.View.extend({

        tagName: 'tr',
        initialize: function() {
            this.template = _.template($('#list_view_template').html());
        },
        render: function() {

            this.$el.html(this.template(this.model.toJSON()));
            return this;
        },
        events: {
            "click .commands .edit"  : "editElement",
            "click .commands .delete" : "deleteElement"
        },

        deleteElement: function() {

            this.model.destroy({
                wait: true,
                error: function(model, response) {
                    alert('A problem has occurred while delenting');
                }

            });
        }

    });

    var ActivityListView = Backbone.View.extend({

        el: $("#activity_table tbody"),
        model: activityList,

        initialize: function() {
            this.model.on('change add delete destroy', this.render, this);
        },

        render: function(){

            this.$el.html('');
            var self = this;

            _.each(this.model.toArray(), function(activity, i) {

                self.$el.append(new ActivityView({model: activity}).render().$el);

            });
            return this;
        }

    });

    var appView = new ActivityListView();
    activityList.fetch();

    // ---- JQuery behavior ----

    $(document).ready(function(){

        function cleanInput (input) {
            return $('<div/>').text(input).text();
        }
        function showError(msg) {
            $('#error_msg span.msg').text(msg);
            $('#error_msg').fadeIn();
        }

        $('#input_form').submit(function(e){
            e.preventDefault();

            // Validation
            var descriptionValue = cleanInput($('#description_input').val());
            var amountValue = cleanInput($('#amount_input').val());

            var patt = new RegExp("^[0-9]+(\.[0-9]+)*$");

            if (descriptionValue.length < 3) {
                showError('Must enter a valid concept or activity description');
                $('#description_input').focus();
                return false;
            }
            if (!patt.test(amountValue)) {
                showError('Must enter a valid amount using dot(.) as decimal separator');
                $('#amount_input').focus();
                return false;
            }

            var newActivity = new ActivityItem({
                description: descriptionValue,
                amount: amountValue
            });

            newActivity.escape()
            newActivity.save({}, {

                success: function(model, response) {

                    model.set({id: response.id})
                    activityList.unshift(newActivity);

                    $('#input_form').find('input').val('');
                    $('#tabs a:first').tab('show');

                },
                error: function(model, response) {
                    alert ('A problem has occurred while adding new record');
                }
            });



        });

    });


})(jQuery);