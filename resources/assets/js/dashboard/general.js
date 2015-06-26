$(document).ready(function() {

    var icon = $('#future-weather-icon').attr('data-icon');

    var skycons = new Skycons({"color": "black"});
    skycons.add("future-weather-icon", icon);
    skycons.play();


});


$(document).ready(function() {
    $('.action .device-toggle').on('click', function(event) {
        event.preventDefault();
        var button = $(this);
        var action = button.parent();
        var room = action.parent().parent();
        var deviceId = action.attr('data-device');
        var state = action.attr('data-state');
        var newState = '0';
        if (state === '1') {
            newState = '0';
        } else if (state === '0') {
            newState = '1';
        }

        $.post('/device/'+deviceId, {'state':newState, '_method':'PUT'})
            .done(function (data) {
                action.attr('data-state', data.state);
                if (data.state === '1') {
                    room.addClass('heater-on');
                    button.addClass('button-active');
                } else if (data.state === '0') {
                    room.removeClass('heater-on');
                    button.removeClass('button-active');
                }
            })
    })
});