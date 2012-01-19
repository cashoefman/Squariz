# Squariz

This App is designed to give you an option to find the last know location of 
one of your friends by phone using Speech Recognition. I am using Tropo for all 
the voice call handling and speech recognition and the FourSquare API to locate 
your friends. It's build using the YII MVC Framework!

Please note that this app was written in only a few hours as a quick demo, so 
there may be bugs, and it's just a starting point that could be developed 
further.

## Requirements

* [PHPFOG](http://phpfog.com) account
* [FOURSQUARE](http://www.foursquare.com) account
* [Tropo](http://www.tropo.com) account
* [YII](http://www.yiiframework.com) account

## Setup

### PHPFog

If you don't have one, create an account on PHPFog and create a new PHP App. 
You will have to make few small modification to the code to make things work 
with your FourSquare App so on to FourSquare

### FOURSQUARE

Setup a new application on FourSquare, this application will be used to 
retrieve your friends latest checkin information. If you don't have one yet you 
can set up a FourSquare developers account [Here](https://developer.foursquare.com/)

When setting up the application you will be asked for a "Download/welcome URL" 
enter http://XXXXX.phpfogapp.com/ and for a "Call back URL" enter 
http://tropodemo.phpfogapp.com/index.php?r=site/auth replace the XXXXX with the 
corresponding CNAME value you used on PHPFog.

Now for a code update, in config/main.php you need to update the lines:

'clientId'=>'FOURSQUARECLIENTIDGOESHERE',

'clientSecret'=>'FOURSQUARECLIENTSECRETGOESHERE',

with the clientID and clientSecret from your FourSquare app.

### Tropo

Create a new application on Tropo, and point it to a new WebAPI application and 
set the URL that powers your new voice application to: 
http://XXXXX.phpfogapp.com/index.php?r=tropo/incoming, again replacing XXXXX
with the CNAME your set for your app on PHPFog.

You can add a local phone number or just use Skype or connect to the app via 
SIP or Phono.


Now that the Tropo step is done, check out the files in views/site you might 
want to update some of those but it's not needed.

That should be about it, call your app and see if it works!


## Copyright

(c) 2012 Cas Hoefman; Licensed under the MIT license, see `LICENSE`
