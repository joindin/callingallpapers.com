/**
 * Created by heiglandreas on 05.06.15.
 */
angular.module('callingallpapers', ['720kb.tooltips'])
    .controller('EventsCtrl', function($scope, $http){
        //$http.get('events.json').then(function(eventsResponse) {
        $http.jsonp('http://api.joind.in/v2.1/events?filter=cfp&verbose=yes&callback=JSON_CALLBACK')
            .success(function(eventsResponse) {
                $scope.events = eventsResponse.events;
            });
    })

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