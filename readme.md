###What Is It
This is a data collection service similar to Xively and data.sparkfun.com

It is a PHP application based on Laravel and can store random pieces of time series data divided into streams.


###Features

- Multiple independent streams of data
- Graphs can be easily generated based on a particular piece of data
- Graphs can also be filtered based on other data
- Pusher is used for a live updating table of stream data

###To Do

- Triggers based on incoming data
- Handle geographic data

###Data storage
Most of the system data is stored in an SQL database but the main feed data is stored in Amazon's Simple DB.
A Simple DB domain is used for each stream and each domain has a hard cap of 10Gb of storage, realistically this shouldn't be a problem but its worth keeping in mind.


###Important
This is the code base for the service I am running for my own use so it will require a few changes to get it running for a different purpose but it shouldn't be to much.