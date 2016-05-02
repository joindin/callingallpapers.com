/**
 * Created by heiglandreas on 05.06.15.
 */
angular.module('callingallpapers', ['720kb.tooltips'])
    .controller('EventsCtrl', ['$scope', '$http', function($scope, $http) {
        $http.get('http://api.callingallpapers.com/v1/cfp')
            .success(function(eventsResponse) {
                $scope.events = eventsResponse.cfps;
            });
    }])

    .filter('dateDiff', function () {
        var magicNumber = (1000 * 60 * 60 * 24);

        return function (toDate, fromDate) {
            toDate = new Date(toDate);
            if (! fromDate) {
                fromDate = Date.now();
            }
            if(toDate && fromDate){
                var dayDiff = Math.floor((toDate - fromDate) / magicNumber);

                if (angular.isNumber(dayDiff)){
                    return dayDiff + 1;
                }
            }
        };
    })

    .filter('filterCfps', function(){
        return function(list, obj) {

            if (typeof list === "undefined") {
                return true;
            }
            
            return list.filter(function(l) {

                if (typeof obj === "undefined") {
                    return true;
                }

                if (obj == '') {
                    return true;
                }

                obj = obj.toLowerCase();

                if (l.description.toLowerCase().indexOf(obj) >= 0) {
                    return true;
                }
                if (l.name.indexOf(obj) >= 0) {
                    return true;
                }
                return l.tags.map(function(value, key) {
                        if (value.indexOf(obj) >= 0) {
                            return true;
                        }
                        return false;
                    }).reduce(function(prev, current) {
                    if (true == prev) {return true;}
                    return current;
                }, false);
            });
        };
    })

    .filter('dateRangeFormat', function(){
        Twix.registerLocale("en-gb", {
            twentyFourHour: true,
                allDaySimple: {
                fn: function(options) {
                    return function() {
                        return options.allDay;
                    };
                },
                slot: 1,
                pre: " "
            },
            dayOfWeek: {
                fn: function(options) {
                    return function(date) {
                        return date.format(options.weekdayFormat);
                    };
                },
                slot: 6,
                pre: " "
            },
            allDayMonth: {
                fn: function(options) {
                    return function(date) {
                        return date.format("" + options.monthFormat + " " + options.dayFormat);
                    };
                },
                slot: 5,
                pre: " "
            },
            month: {
                fn: function(options) {
                    return function(date) {
                        return date.format(options.monthFormat);
                    };
                },
                slot: 3,
                pre: " "
            },
            date: {
                fn: function(options) {
                    return function(date) {
                        return date.format(options.dayFormat);
                    };
                },
                slot: 2,
                pre: " ",
            },
            year: {
                fn: function(options) {
                    return function(date) {
                        return date.format(options.yearFormat);
                    };
                },
                slot: 4,
                pre: ", "
            },
            time: {
                fn: function(options) {
                    return function(date) {
                        var str;
                        str = date.minutes() === 0 && options.implicitMinutes && !options.twentyFourHour ? date.format(options.hourFormat) : date.format("" + options.hourFormat + ":" + options.minuteFormat);
                        if (!options.groupMeridiems && !options.twentyFourHour) {
                            if (options.spaceBeforeMeridiem) {
                                str += " ";
                            }
                            str += date.format(options.meridiemFormat);
                        }
                        return str;
                    };
                },
                slot: 7,
                pre: ", "
            },
            meridiem: {
                fn: function(options) {
                    return (function(_this) {
                        return function(t) {
                            return t.format(options.meridiemFormat);
                        };
                    })(this);
                },
                slot: 8,
                    pre: function(options) {
                    if (options.spaceBeforeMeridiem) {
                        return " ";
                    } else {
                        return "";
                    }
                }
            }
        });
        return function(toDate, fromDate) {

            if (! fromDate) {
                fromDate = Date.now();
            }
            if (toDate && fromDate) {
                moment.locale('en-gb');
                fromDate = moment(fromDate);
                return moment(toDate).twix(fromDate,{allDay: true}).format({
                    yearFormat: "YYYY",
                    monthFormat: "MMMM",
                    weekdayFormat: "ddd",
                    dayFormat: "D.",
                    meridiemFormat: "A",
                    hourFormat: "h",
                    minuteFormat: "mm",
                    allDay: "all day",

                });
            }
        };
    });