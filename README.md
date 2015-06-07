# Calling all Papers

This site shows open Calls for Papers (CfPs) that are available via the [joind.in-API](http://api.joind.in)

## Limitations

This is just a draft currently. The thing that comes before "Pre-Alpha" you could say. Currently the
data is a "hard-coded" JSON-response from the joind.in-API. To get current data you can run the following command
in the root folder of the repo:

    curl -o events.json http://api.joind.in/v2.1/events?filter=cfp

## Setup

This site runs with [Angular.js](https://angularjs.org/).

Angular.js and all the other dependencies can be installed using [Bower](http://bower.io/). To
install bower have a look at http://bower.io/#install-bower. It basically boils down to running

    npm install -g bower

which means you also need node and npm running.

To get the dependencies go to the root-directory of the repository and call

    bower install

That should install all necessary dependencies into ```/inc/lib``` and you should be able to
call the ```index.html``` to get an overview of open CfPs


