var date =  new Date();
date.setDate(date.getDate());


$(document).ready(function() {
    // you may need to change this code if you are not using Bootstrap Datepicker
    $('.js-datepicker').datepicker({
        language: 'fr',
        /*endDate: '+18m',
        format: {
            toDisplay: function (date, format, language) {
            var d = new Date(date);
            d.setDate(d.getDate());
            return d.toISOString();
        },
            toValue: function (date, format, language) {
                var d = new Date(date);
                d.setDate(d.getDate());
                return new Date(d);
            }
        },
        /*dateDisabled:
        beforeShowDay: function(date){
            return false
        }
        daysOfWeekDisabled: '[0, 2]',*/
    });
});
