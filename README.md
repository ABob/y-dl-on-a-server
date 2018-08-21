# y-dl-on-a-server
Run youtube-dl on your server and let it download and convert videos on it. You then can download or stream the result from there to your local device.

I primarily use it to convert videos to audio files and load it on my smart phone to hear it later and offline (therefor the interface is mobile friendly written in Bootstrap). There are many sites in the web that offer such a service for you - but they all seemed a little bit shady to me, so I wrote my own solution. 

WARNING: Don't use this at a public web directory! This is a very simple script with no concentration on security! Hide it in a private, access restricted place!

The basic functionality is working: paste links into the box on y-dl.php, add additional arguments for youtube-dl (all supported), download files to the /dls subfolder and display them on a website (/files.php). Also, an RSS feed will be created (created/displayed by opening /rss.php), which can be used to hear the downloaded files comfortable in an podcast app (for this, I recommend AntennaPod).

To use it, just copy all files/folders in a directory on your web server. You need youtube-dl and php5 to be installed on your server. Also, you have to grant write permissions or change ownership of the "/dls" subfolder so the webserver can write the downloaded files in there (for example with: "chown -R YOUR_WEBSERVER_USER:YOUR_WEBSERVER_USER dls").
The project uses Twitters Bootstrap (and with it JQuery) to create the website. They are included over links to CDNs, but you can download the needed files to your disk and link those, of course. It should work without them nevertheless.

I have some additional features in mind that will probably be added in the near future, as well as an app for android devices (already in progress) for easier access/handling on smart phones.
