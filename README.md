# y-dl-on-a-server
Run youtube-dl on your server and let it download and convert videos on it. You then can download or stream the result from there to your local device.

I primarily use it to convert videos to audio files and load it on my smart phone to hear it later and offline (therefor the interface is mobile friendly written in bootstrap). There are many sites in the web that offer such a service for you - but they all seemed a little bit shady to me, so I wrote my own solution. 

The basic functionality is working: paste links into the box on y-dl.php, add additional arguments for youtube-dl (all supported), download files to the /dls subfolder and display them on a website (files.php). 

I have some additional features in mind that will probably be added in the near future, as well as an app for android devices (already in progress) for easier access/handling on smart phones.
