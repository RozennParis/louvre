var currentYear= (new Date()).getUTCFullYear();

function NotOpenedDates(year){
    var newYearDay = new Date(Date.UTC(year, 0, 1));
    var laborDay = new Date(Date.UTC(year, 4, 1));
    var armistice1945 = new Date(Date.UTC(year, 4, 8));
    var nationalDay = new Date(Date.UTC(year, 6, 14));
    var assumption = new Date(Date.UTC(year, 7, 15));
    var allSaintsDay = new Date(Date.UTC(year, 10, 1));
    var armistice1918 = new Date(Date.UTC(year, 10, 11));
    var christmasDay = new Date(Date.UTC(year, 11, 25));

    var G = year%19;
    var C = Math.floor(year/100);
    var H = (C - Math.floor(C/4) - Math.floor((8*C+13)/25) + 19*G + 15)%30;
    var I = H - Math.floor(H/28)*(1 - Math.floor(H/28)*Math.floor(29/(H + 1))*Math.floor((21 - G)/11));
    var J = (year + Math.floor(year/4) + I + 2 - C + Math.floor(C/4))%7;
    var L = I - J;
    var easterMonth = 3 + Math.floor((L + 40)/44);
    var easterDay = L + 28 - 31*Math.floor(easterMonth/4);
    var easterDate = new Date(Date.UTC(year, easterMonth-1, easterDay));
    var easterMonday = new Date(Date.UTC(year, easterMonth-1, easterDay+1));
    var ascensionDay = new Date(Date.UTC(year, easterMonth-1, easterDay+39));
    var whitMonday = new Date(Date.UTC(year, easterMonth-1, easterDay+50));

    return  [newYearDay, easterDate, easterMonday, laborDay, armistice1945, ascensionDay, whitMonday,nationalDay, assumption, allSaintsDay, armistice1918, christmasDay];
}


var dates = [].concat(NotOpenedDates(currentYear)).concat(NotOpenedDates(currentYear+1)).concat(NotOpenedDates(currentYear+2));
console.log(dates);

$(document).ready(function() {
    $('.js-datepicker').datepicker({
        language: 'fr',
        startDate: 'today',
        endDate: '+18m',
        todayHighlight: true,
        autoclose: true,
        datesDisabled: dates,
        daysOfWeekDisabled: '[0, 2]'
    });
});
