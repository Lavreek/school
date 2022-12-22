const $ = require('jquery');

(function($) {

	"use strict";


	$(document).ready(function () {
    function c(passed_month, passed_year, calNum) {
        // var calendar = calNum == 0 ? calendars.cal1 : calendars.cal2;

        switch(calNum) {
            case 2:  // if (x === 'value1')
                var calendar = calendars.cal2;
                break;
            case 2:  // if (x === 'value1')
                var calendar = calendars.cal2;
                break;
            case 3:  // if (x === 'value1')
                var calendar = calendars.cal3;
                break;
            case 4:  // if (x === 'value1')
                var calendar = calendars.cal4;
                break;
            case 5:  // if (x === 'value1')
                var calendar = calendars.cal5;
                break;
            case 6:  // if (x === 'value1')
                var calendar = calendars.cal6;
                break;
            case 7:  // if (x === 'value1')
                var calendar = calendars.cal7;
                break;
            case 8:  // if (x === 'value1')
                var calendar = calendars.cal8;
                break;
            case 9:  // if (x === 'value1')
                var calendar = calendars.cal9;
                break;
            case 10:  // if (x === 'value1')
                var calendar = calendars.cal10;
                break;
            case 11:  // if (x === 'value1')
                var calendar = calendars.cal11;
                break;
            case 12:  // if (x === 'value1')
                var calendar = calendars.cal12;
                break;

            default:
                var calendar = calendars.cal1;
                break;
        }
        makeWeek(calendar.weekline);
        calendar.datesBody.empty();
        var calMonthArray = makeMonthArray(passed_month, passed_year);
        var r = 0;
        var u = false;
        while (!u) {
            if (daysArray[r] == calMonthArray[0].weekday) {
                u = true
            } else {
                calendar.datesBody.append('<div class="blank"></div>');
                r++;
            }
        }
        for (var cell = 0; cell < 42 - r; cell++) { // 42 date-cells in calendar
            if (cell >= calMonthArray.length) {
                calendar.datesBody.append('<div class="blank"></div>');
            } else {
                var shownDate = calMonthArray[cell].day;
                var iter_date = new Date(passed_year, passed_month, shownDate);
                if (
                (
                (shownDate != today.getDate() && passed_month == today.getMonth()) || passed_month != today.getMonth()) && iter_date < today) {
                    var m = '<div class="past-date select date-' + month + '-' + shownDate + '">';
                } else {
                    var m = checkToday(iter_date) ? '<div class="today">' : "<div>";
                }
                calendar.datesBody.append(m + shownDate + "</div>");
            }
        }

        var color = "#444444";
        calendar.calHeader.find("h2").text(i[passed_month] + " " + passed_year);
        calendar.weekline.find("div").css("color", color);
        calendar.datesBody.find(".today").css("color", "#00bdaa");

        // find elements (dates) to be clicked on each time
        // the calendar is generated
        var clicked = false;
        selectDates(selected);

        clickedElement = calendar.datesBody.find('div');
        clickedElement.on("click", function () {
            clicked = $(this);
            var whichCalendar = calendar.name;

            if (firstClick && secondClick) {
                thirdClicked = getClickedInfo(clicked, calendar);
                var firstClickDateObj = new Date(firstClicked.year,
                firstClicked.month,
                firstClicked.date);
                var secondClickDateObj = new Date(secondClicked.year,
                secondClicked.month,
                secondClicked.date);
                var thirdClickDateObj = new Date(thirdClicked.year,
                thirdClicked.month,
                thirdClicked.date);
                if (secondClickDateObj > thirdClickDateObj && thirdClickDateObj > firstClickDateObj) {
                    secondClicked = thirdClicked;
                    // then choose dates again from the start :)
                    bothCals.find(".calendar_content").find("div").each(function () {
                        $(this).removeClass("selected");
                    });
                    selected = {};
                    selected[firstClicked.year] = {};
                    selected[firstClicked.year][firstClicked.month] = [firstClicked.date];
                    selected = addChosenDates(firstClicked, secondClicked, selected);
                } else { // reset clicks
                    selected = {};
                    firstClicked = [];
                    secondClicked = [];
                    firstClick = false;
                    secondClick = false;
                    bothCals.find(".calendar_content").find("div").each(function () {
                        $(this).removeClass("selected");
                    });
                }
            }
            if (!firstClick) {
                firstClick = true;
                firstClicked = getClickedInfo(clicked, calendar);
                selected[firstClicked.year] = {};
                selected[firstClicked.year][firstClicked.month] = [firstClicked.date];
            } else {
                secondClick = true;
                secondClicked = getClickedInfo(clicked, calendar);

                // what if second clicked date is before the first clicked?
                var firstClickDateObj = new Date(firstClicked.year,
                firstClicked.month,
                firstClicked.date);
                var secondClickDateObj = new Date(secondClicked.year,
                secondClicked.month,
                secondClicked.date);

                if (firstClickDateObj > secondClickDateObj) {

                    var cachedClickedInfo = secondClicked;
                    secondClicked = firstClicked;
                    firstClicked = cachedClickedInfo;
                    selected = {};
                    selected[firstClicked.year] = {};
                    selected[firstClicked.year][firstClicked.month] = [firstClicked.date];

                } else if (firstClickDateObj.getTime() == secondClickDateObj.getTime()) {
                    selected = {};
                    firstClicked = [];
                    secondClicked = [];
                    firstClick = false;
                    secondClick = false;
                    $(this).removeClass("selected");
                }


                // add between dates to [selected]
                selected = addChosenDates(firstClicked, secondClicked, selected);
            }
            selectDates(selected);
        });

    }

    function selectDates(selected) {
        if (!$.isEmptyObject(selected)) {
            var dateElements1 = datesBody1.find('div');
            var dateElements2 = datesBody2.find('div');

            function highlightDates(passed_year, passed_month, dateElements) {
                if (passed_year in selected && passed_month in selected[passed_year]) {
                    var daysToCompare = selected[passed_year][passed_month];
                    for (var d in daysToCompare) {
                        dateElements.each(function (index) {
                            if (parseInt($(this).text()) == daysToCompare[d]) {
                                $(this).addClass('selected');
                            }
                        });
                    }

                }
            }

            highlightDates(year, month, dateElements1);
            highlightDates(nextYear, nextMonth, dateElements2);
        }
    }

    function makeMonthArray(passed_month, passed_year) { // creates Array specifying dates and weekdays
        var e = [];
        for (var r = 1; r < getDaysInMonth(passed_year, passed_month) + 1; r++) {
            e.push({
                day: r,
                // Later refactor -- weekday needed only for first week
                weekday: daysArray[getWeekdayNum(passed_year, passed_month, r)]
            });
        }
        return e;
    }

    function makeWeek(week) {
        week.empty();
        for (var e = 0; e < 7; e++) {
            week.append("<div>" + daysArray[e].substring(0, 3) + "</div>")
        }
    }

    function getDaysInMonth(currentYear, currentMon) {
        return (new Date(currentYear, currentMon + 1, 0)).getDate();
    }

    function getWeekdayNum(e, t, n) {
        return (new Date(e, t, n)).getDay();
    }

    function checkToday(e) {
        var todayDate = today.getFullYear() + '/' + (today.getMonth() + 1) + '/' + today.getDate();
        var checkingDate = e.getFullYear() + '/' + (e.getMonth() + 1) + '/' + e.getDate();
        return todayDate == checkingDate;

    }

    function getAdjacentMonth(curr_month, curr_year, direction) {
        var theNextMonth;
        var theNextYear;
        if (direction == "next") {
            theNextMonth = (curr_month + 1) % 12;
            theNextYear = (curr_month == 11) ? curr_year + 1 : curr_year;
        } else {
            theNextMonth = (curr_month == 0) ? 11 : curr_month - 1;
            theNextYear = (curr_month == 0) ? curr_year - 1 : curr_year;
        }
        return [theNextMonth, theNextYear];
    }

    function b() {
        today = new Date;
        year = today.getFullYear();
        month = today.getMonth();
        var nextDates = getAdjacentMonth(month, year, "next");
        nextMonth = nextDates[0];
        nextYear = nextDates[1];
    }

    var e = 480;

    var today;
    var year,
    month,
    nextMonth,
    nextYear;

    var r = [];
    var i = [
        "January",
        "Feburary",
        "March",
        "April",
        "May",
        "June",
        "July",
        "August",
        "September",
        "October",
        "November",
        "December"];
    var daysArray = [
        "Sunday",
        "Monday",
        "Tuesday",
        "Wednesday",
        "Thursday",
        "Friday",
        "Saturday"];

    var cal1 = $("#calendar_first");
    var calHeader1 = cal1.find(".calendar_header");
    var weekline1 = cal1.find(".calendar_weekdays");
    var datesBody1 = cal1.find(".calendar_content");

    var cal2 = $("#calendar_second");
    var calHeader2 = cal2.find(".calendar_header");
    var weekline2 = cal2.find(".calendar_weekdays");
    var datesBody2 = cal2.find(".calendar_content");

    var cal3 = $("#calendar_third");
    var calHeader3 = cal3.find(".calendar_header");
    var weekline3 = cal3.find(".calendar_weekdays");
    var datesBody3 = cal3.find(".calendar_content");

        var cal4 = $("#calendar_fourth");
        var calHeader4 = cal4.find(".calendar_header");
        var weekline4 = cal4.find(".calendar_weekdays");
        var datesBody4 = cal4.find(".calendar_content");

        var cal5 = $("#calendar_fifth");
        var calHeader5 = cal5.find(".calendar_header");
        var weekline5 = cal5.find(".calendar_weekdays");
        var datesBody5 = cal5.find(".calendar_content");

        var cal6 = $("#calendar_sixth");
        var calHeader6 = cal6.find(".calendar_header");
        var weekline6 = cal6.find(".calendar_weekdays");
        var datesBody6 = cal6.find(".calendar_content");

        var cal7 = $("#calendar_seventh");
        var calHeader7 = cal7.find(".calendar_header");
        var weekline7 = cal7.find(".calendar_weekdays");
        var datesBody7 = cal7.find(".calendar_content");

        var cal8 = $("#calendar_eigth");
        var calHeader8 = cal8.find(".calendar_header");
        var weekline8 = cal8.find(".calendar_weekdays");
        var datesBody8 = cal8.find(".calendar_content");

        var cal9 = $("#calendar_ninth");
        var calHeader9 = cal9.find(".calendar_header");
        var weekline9 = cal9.find(".calendar_weekdays");
        var datesBody9 = cal9.find(".calendar_content");

        var cal10 = $("#calendar_tenth");
        var calHeader10 = cal10.find(".calendar_header");
        var weekline10 = cal10.find(".calendar_weekdays");
        var datesBody10 = cal10.find(".calendar_content");

        var cal11 = $("#calendar_eleventh");
        var calHeader11 = cal11.find(".calendar_header");
        var weekline11 = cal11.find(".calendar_weekdays");
        var datesBody11 = cal11.find(".calendar_content");

        var cal12 = $("#calendar_twelfth");
        var calHeader12 = cal12.find(".calendar_header");
        var weekline12 = cal12.find(".calendar_weekdays");
        var datesBody12 = cal12.find(".calendar_content");

    var bothCals = $(".calendar");

    var switchButton = bothCals.find(".calendar_header").find('.switch-month');

    var calendars = {
        "cal1": {
            "name": "first",
                "calHeader": calHeader1,
                "weekline": weekline1,
                "datesBody": datesBody1
        },
            "cal2": {
            "name": "second",
                "calHeader": calHeader2,
                "weekline": weekline2,
                "datesBody": datesBody2
        },
            "cal3": {
            "name": "third",
            "calHeader": calHeader3,
            "weekline": weekline3,
            "datesBody": datesBody3
        },
            "cal4": {
            "name": "fourth",
            "calHeader": calHeader4,
            "weekline": weekline4,
            "datesBody": datesBody4
        },
            "cal5": {
            "name": "fifth",
            "calHeader": calHeader5,
            "weekline": weekline5,
            "datesBody": datesBody5
        },
            "cal6": {
            "name": "sixth",
            "calHeader": calHeader6,
            "weekline": weekline6,
            "datesBody": datesBody6
        },
            "cal7": {
            "name": "seventh",
            "calHeader": calHeader7,
            "weekline": weekline7,
            "datesBody": datesBody7
        },
            "cal8": {
            "name": "eigth",
            "calHeader": calHeader8,
            "weekline": weekline8,
            "datesBody": datesBody8
        },
            "cal9": {
            "name": "ninth",
            "calHeader": calHeader9,
            "weekline": weekline9,
            "datesBody": datesBody9
        },
            "cal10": {
            "name": "tenth",
            "calHeader": calHeader10,
            "weekline": weekline10,
            "datesBody": datesBody10
        },
            "cal11": {
            "name": "eleventh",
            "calHeader": calHeader11,
            "weekline": weekline11,
            "datesBody": datesBody11
        },
            "cal12": {
            "name": "twelfth",
            "calHeader": calHeader12,
            "weekline": weekline12,
            "datesBody": datesBody12
        }
    }


    var clickedElement;
    var firstClicked,
    secondClicked,
    thirdClicked;
    var firstClick = false;
    var secondClick = false;
    var selected = {};

    b();

    for (let monthIndex = -3; monthIndex < 4; monthIndex++) {
        let calcMonth;
        let calcYear = year;
        calcMonth = (monthIndex + month);

        if ((month + monthIndex) >= 12) {
            calcMonth = (monthIndex + month) % 12;
            calcYear++;

        }
        if ((month + monthIndex) < 0) {
            calcMonth = 12 - (monthIndex + month);
            calcYear--;
        }

        c(calcMonth, calcYear, 4 + monthIndex);
    }

        // c(nextMonth, nextYear, 0);

        switchButton.on("click", function () {
        var clicked = $(this);
        var generateCalendars = function (e) {
            var nextDatesFirst = getAdjacentMonth(month, year, e);
            var nextDatesSecond = getAdjacentMonth(nextMonth, nextYear, e);
            month = nextDatesFirst[0];
            year = nextDatesFirst[1];
            nextMonth = nextDatesSecond[0];
            nextYear = nextDatesSecond[1];

            c(month, year, 0);
            c(nextMonth, nextYear, 1);
        };
        if (clicked.attr("class").indexOf("left") != -1) {
            generateCalendars("previous");
        } else {
            generateCalendars("next");
        }
        clickedElement = bothCals.find(".calendar_content").find("div");
    });


    //  Click picking stuff
    function getClickedInfo(element, calendar) {
        var clickedInfo = {};
        var clickedCalendar,
        clickedMonth,
        clickedYear;
        clickedCalendar = calendar.name;
        clickedMonth = clickedCalendar == "first" ? month : nextMonth;
        clickedYear = clickedCalendar == "first" ? year : nextYear;
        clickedInfo = {
            "calNum": clickedCalendar,
                "date": parseInt(element.text()),
                "month": clickedMonth,
                "year": clickedYear
        }
        return clickedInfo;
    }


    // Finding between dates MADNESS. Needs refactoring and smartening up :)
    function addChosenDates(firstClicked, secondClicked, selected) {
        if (secondClicked.date > firstClicked.date || secondClicked.month > firstClicked.month || secondClicked.year > firstClicked.year) {

            var added_year = secondClicked.year;
            var added_month = secondClicked.month;
            var added_date = secondClicked.date;

            if (added_year > firstClicked.year) {
                // first add all dates from all months of Second-Clicked-Year
                selected[added_year] = {};
                selected[added_year][added_month] = [];
                for (var i = 1;
                i <= secondClicked.date;
                i++) {
                    selected[added_year][added_month].push(i);
                }

                added_month = added_month - 1;
                while (added_month >= 0) {
                    selected[added_year][added_month] = [];
                    for (var i = 1;
                    i <= getDaysInMonth(added_year, added_month);
                    i++) {
                        selected[added_year][added_month].push(i);
                    }
                    added_month = added_month - 1;
                }

                added_year = added_year - 1;
                added_month = 11; // reset month to Dec because we decreased year
                added_date = getDaysInMonth(added_year, added_month); // reset date as well

                // Now add all dates from all months of inbetween years
                while (added_year > firstClicked.year) {
                    selected[added_year] = {};
                    for (var i = 0; i < 12; i++) {
                        selected[added_year][i] = [];
                        for (var d = 1; d <= getDaysInMonth(added_year, i); d++) {
                            selected[added_year][i].push(d);
                        }
                    }
                    added_year = added_year - 1;
                }
            }

            if (added_month > firstClicked.month) {
                if (firstClicked.year == secondClicked.year) {
                    selected[added_year][added_month] = [];
                    for (var i = 1;
                    i <= secondClicked.date;
                    i++) {
                        selected[added_year][added_month].push(i);
                    }
                    added_month = added_month - 1;
                }
                while (added_month > firstClicked.month) {
                    selected[added_year][added_month] = [];
                    for (var i = 1;
                    i <= getDaysInMonth(added_year, added_month);
                    i++) {
                        selected[added_year][added_month].push(i);
                    }
                    added_month = added_month - 1;
                }
                added_date = getDaysInMonth(added_year, added_month);
            }

            for (var i = firstClicked.date + 1;
            i <= added_date;
            i++) {
                selected[added_year][added_month].push(i);
            }
        }
        return selected;
    }
});
    $(document).ready(function(){
        var outerContent = $('.calendar-flex');
        var innerContent = $('.calendar-flex > div');

        outerContent.scrollLeft((innerContent.width()) * 2.5);
    });
})($);
