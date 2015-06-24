###What Is It
This is a data collection service similar to Xively and data.sparkfun.com

It is a PHP application based on Laravel and can store random pieces of time series data divided into streams.


The purpose of this application has changed slightly to be more of a home automation / data driven system.
The existing functionality has been retained but additional bits have been added and the focus of the interface has changed.

###Features

- Multiple independent streams of data
- Graphs can be easily generated based on a particular piece of data
- Graphs can also be filtered based on other data
- Pusher is used for a live updating table of stream data
- Triggers based on incoming data - send a message via pushover or set a local variable

###To Do

- Handle geographic data
- A formula type system to carry out more advanced operations
- Set one or more variables to be returned when data is sent

###Data storage
Most of the system data is stored in an SQL database but the main feed data is stored in Amazon's Simple DB.
A Simple DB domain is used for each stream and each domain has a hard cap of 10Gb of storage, realistically this shouldn't be a problem but its worth keeping in mind.


###Important
This is the code base for the service I am running for my own use so it will require a few changes to get it running for a different purpose but it shouldn't be to much.