/**
 * Created by heiglandreas on 05.06.15.
 */
angular.module('callingallpapers', ['720kb.tooltips', 'ngSanitize'])
    .controller('EventsCtrl', ['$scope', '$http', function($scope, $http) {
        $http.get('https://api.callingallpapers.com/v1/cfp')
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

                if (obj === '') {
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
                    if (true === prev) {return true;}
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
    })

    .directive( "ngTextTruncate", [ "$compile", "ValidationServices", "CharBasedTruncation", "WordBasedTruncation",
            function( $compile, ValidationServices, CharBasedTruncation, WordBasedTruncation ) {
                return {
                    restrict: "AE",
                    scope: {
                        text: "@ngTextTruncate",
                        charsThreshould: "@ngTtCharsThreshold",
                        wordsThreshould: "@ngTtWordsThreshold",
                        customMoreLabel: "@ngTtMoreLabel",
                        customLessLabel: "@ngTtLessLabel"
                    },
                    controller: function( $scope, $element, $attrs ) {
                        $scope.toggleShow = function() {
                            $scope.open = !$scope.open;
                        };

                        $scope.useToggling = $attrs.ngTtNoToggling === undefined;
                    },
                    link: function( $scope, $element, $attrs ) {
                        $scope.open = false;

                        ValidationServices.failIfWrongThreshouldConfig( $scope.charsThreshould, $scope.wordsThreshould );

                        var CHARS_THRESHOLD = parseInt( $scope.charsThreshould );
                        var WORDS_THRESHOLD = parseInt( $scope.wordsThreshould );

                        $scope.$watch( "text", function() {
                            $element.empty();

                            if( CHARS_THRESHOLD ) {
                                if( $scope.text && CharBasedTruncation.truncationApplies( $scope, CHARS_THRESHOLD ) ) {
                                    CharBasedTruncation.applyTruncation( CHARS_THRESHOLD, $scope, $element );

                                } else {
                                    $element.append( $scope.text );
                                }

                            } else {

                                if( $scope.text && WordBasedTruncation.truncationApplies( $scope, WORDS_THRESHOLD ) ) {
                                    WordBasedTruncation.applyTruncation( WORDS_THRESHOLD, $scope, $element );

                                } else {
                                    $element.append( $scope.text );
                                }

                            }
                        } );
                    }
                };
            }] )



        .factory( "ValidationServices", function() {
            return {
                failIfWrongThreshouldConfig: function( firstThreshould, secondThreshould ) {
                    if( (! firstThreshould && ! secondThreshould) || (firstThreshould && secondThreshould) ) {
                        throw "You must specify one, and only one, type of threshould (chars or words)";
                    }
                }
            };
        })



        .factory( "CharBasedTruncation", [ "$compile", function( $compile ) {
            return {
                truncationApplies: function( $scope, threshould ) {
                    return $scope.text.length > threshould;
                },

                applyTruncation: function( threshould, $scope, $element ) {
                    if( $scope.useToggling ) {
                        var el = angular.element(    "<span>" +
                            $scope.text.substr( 0, threshould ) +
                            "<span ng-show='!open'>...</span>" +
                            "<span class='btn-link ngTruncateToggleText' " +
                            "ng-click='toggleShow()'" +
                            "ng-show='!open'>" +
                            " " + ($scope.customMoreLabel ? $scope.customMoreLabel : "More") +
                            "</span>" +
                            "<span ng-show='open'>" +
                            $scope.text.substring( threshould ) +
                            "<span class='btn-link ngTruncateToggleText'" +
                            "ng-click='toggleShow()'>" +
                            " " + ($scope.customLessLabel ? $scope.customLessLabel : "Less") +
                            "</span>" +
                            "</span>" +
                            "</span>" );
                        $compile( el )( $scope );
                        $element.append( el );

                    } else {
                        $element.append( $scope.text.substr( 0, threshould ) + "..." );

                    }
                }
            };
        }])



        .factory( "WordBasedTruncation", [ "$compile", function( $compile ) {
            return {
                truncationApplies: function( $scope, threshould ) {
                    return $scope.text.split( " " ).length > threshould;
                },

                applyTruncation: function( threshould, $scope, $element ) {
                    var splitText = $scope.text.split( " " );
                    if( $scope.useToggling ) {
                        var el = angular.element(    "<span>" +
                            splitText.slice( 0, threshould ).join( " " ) + " " +
                            "<span ng-show='!open'>...</span>" +
                            "<span class='btn-link ngTruncateToggleText' " +
                            "ng-click='toggleShow()'" +
                            "ng-show='!open'>" +
                            " " + ($scope.customMoreLabel ? $scope.customMoreLabel : "<i class='fa fa-angle-double-down'></i>") +
                            "</span>" +
                            "<span ng-show='open'>" +
                            splitText.slice( threshould, splitText.length ).join( " " ) +
                            "<span class='btn-link ngTruncateToggleText'" +
                            "ng-click='toggleShow()'>" +
                            " " + ($scope.customLessLabel ? $scope.customLessLabel : "<i class='fa fa-angle-double-up'></i>") +
                            "</span>" +
                            "</span>" +
                            "</span>" );
                        $compile( el )( $scope );
                        $element.append( el );

                    } else {
                        $element.append( splitText.slice( 0, threshould ).join( " " ) + "..." );
                    }
                }
            };
        }]);

