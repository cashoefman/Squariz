# Squariz

We build [Squariz](http://squariz.phpfogapp.com) as a demo for an upcoming presentation, it is a [Foursquare](http://foursquare.com) friend finder by phone application with voice recognition powered by [Tropo](http://tropo.com) build using the [YII](http://yiiframeworks.com) MVC Framework and the [Twitter Bootstrap](http://twitter.github.com/bootstrap/) toolkit!

This Application is designed to give you a way to find the last know location of one of your friends by phone using Speech Recognition. We are using Tropo for all the voice call handling and speech recognition and the Foursquare API to locate your friends.

Please note that this app was written in only a few hours as a quick demo, so there may be bugs, and it’s just a starting point that could be developed further.

## Requirements

* [YII](http://www.yiiframework.com) MVC Framework
* [Bootstrap](http://twitter.github.com/bootstrap/), the Twitter Bootstrap toolkit)
* [PHPFOG](http://phpfog.com) Account
* [FOURSQUARE](http://www.foursquare.com) Account & Application
* [Tropo](http://www.tropo.com) Account


## Setup

As you know from previous post we really like MVC so we build the whole thing using the Yii MVC Framework. Yii is a high-performance PHP framework best for developing Web 2.0 applications. Yii comes with rich features: MVC, DAO/ActiveRecord, I18N/L10N, caching, authentication and role-based access control, scaffolding, testing, etc. It can reduce your development time significantly.

So to make it a little more interesting we decided to also use Bootstrap from Twitter. Bootstrap is a toolkit from Twitter designed to kickstart development of webapps and sites. It includes base CSS and HTML for typography, forms, buttons, tables, grids, navigation, and more. A little late we figured out Yii now also has a Yii Twitter Bootstrap extension a Yii integration for Twitter’s web development toolkit.


### PHPFog

If you don’t have one, create an account on PHPFog and create a new PHP App and set the CNAME for the app. Then grab the source code for this app from GitHub, you will have to make few small modification to the code to make things work with your Foursquare App so on to FourSquare first.

### FOURSQUARE

Setup a new application on Foursquare, this application will be used to retrieve your friends latest checkin information. If you don’t have one yet set up a Foursquare developers account first.

When setting up the application you will be asked for a “Download/welcome URL” enter http://XXXXX.phpfogapp.com/ and for a “Call back URL” enter http://XXXXX.phpfogapp.com/index.php?r=site/auth replace the XXXXX with the corresponding CNAME value you setup on PHPFog.

Now for a code update, in config/main.php you need to update the lines:

‘clientId’=>’<FOURSQUARECLIENTID>’,

‘clientSecret’=>’<FOURSQUARECLIENTSECRET>’,

with the clientID and clientSecret from your Foursquare app.

While you are at it, also set the 'canonicalDomain' under 'params' and the'connectionString', the ‘username’ and ‘password’ under ‘db’, these are required and you can find the database information in the PHPfog App Panel under database. Optionally set the ‘FromName’ and ‘From’ in the ‘mailer’ section.

### Tropo

Next you will have to create a new application on Tropo, point it to a new WebAPI application and set the URL that powers your new Voice application and the SMS application to: http://XXXXX.phpfogapp.com/index.php?r=tropo/incoming, again replacing XXXXX with the CNAME your set for your app on PHPFog.

You can add a local phone number or just use Skype or connect to the app via SIP or Phono. You might want to update the phone phone number/skype number in /view/email/registration.php with your numbers.

Now that the Tropo step is done, check out the other files in views/site you might want to update some of those but it’s not needed.

That should be about it, call or SMS your app and see if it works!

## Copyright

(c) 2012 Cas Hoefman; Licensed under the MIT license, see `LICENSE`
