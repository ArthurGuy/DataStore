<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <link rel="manifest" href="manifest.json">
    <meta name="theme-color" content="#5898D8">

    <meta name="mobile-web-app-capable" content="yes">
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1">

    <link rel="icon" type="image/vnd.microsoft.icon" href="https://s3-eu-west-1.amazonaws.com/static.arthurguy.co.uk/images/ArthurGuy.ico" />

    <title>Home Dashboard</title>

    <link href="/css/dashboard.css" rel="stylesheet">

</head>
<body>

<div class="container-fluid">

    <div id="dashboard">



        <section class="forecast">

            <div v-if="!forecastAvailable">
                <div class="forecast-loading">
                    <span class="button-icon glyphicons glyphicons-refresh"></span>
                    Loading weather forecast...
                </div>
            </div>

            <div v-if="forecastAvailable">

                <h1 class="daySummary" v-text="forecast.dayWeather.daySummary"></h1>

                <div class="keyForecast">
                    <weather-icon width="200" height="200" icon="{{ forecast.futureForecast.icon }}"></weather-icon>

                    <span class="primaryTemp"><temperature value="{{ forecast.temperature }}"></temperature></span>

                    <span class="tempRange"><temperature value="{{ forecast.dayWeather.dayMinTemperature }}"></temperature> - <temperature value="{{ forecast.dayWeather.dayMaxTemperature }}"></temperature></span>

                    <span v-text="forecast.condition" class="condition"></span>
                </div>

            </div>

        </section>



        <div class="room-list">

            <room v-repeat="rooms"></room>

        </div>



        <h2 style="text-align: center;">
            <span v-class="hidden : !location.home">
                <span class="glyphicon glyphicon-home"></span> Home
            </span>
            <span v-class="hidden : location.home">
                <span class="glyphicons glyphicons-person-running"></span> Away
            </span>
        </h2>

        <div v-class="refreshing : loading" class="refresh">
            <span class="button-icon glyphicons glyphicons-refresh" v-on="click: refreshData"></span>
        </div>

        <div style="text-align: center">
            <span class="glyphicons glyphicons-riflescope" v-on="click: fetchCordinates"></span>
            <span>{{ latitude }}</span>
            <span>{{ longitude }}</span>
        </div>

        <!--
        <span class="glyphicons glyphicons-riflescope"></span>Geolocate
        <span class="glyphicons glyphicons-ban"></span>device error
        -->

        <small>
            Local: <?php echo json_decode(file_get_contents(base_path('resources/assets/versions.json')), true)['dashboard']; ?> |
            Remote: <span>{{ meta.version }}</span>
        </small>

    </div>

<script id="room-template" type="x-template">
    <div v-class="heater-on : heater && heater.state, auto-mode : mode == 'auto'" class="room">

        <div class="heading">

            <span class="name">
                {{ name }}
                <span v-if="hasWarning" class="glyphicons glyphicons-warning-sign" title="No Updates since {{ last_updated }}" data-toggle="tooltip" data-placement="top" style="color:#FF7100;font-size: 16px;padding-top: 5px;"></span>
            </span>
            <span class="primaryTemp"><temperature value="{{ temperature }}"></temperature> | {{ humidity }}%</span>

        </div>

        <span class="condition hidden">{{ condition }}</span>

        <span class="heater-status">Maintaining <temperature value="{{ target_temperature }}"></temperature></span>

        <div class="action-row">

            <div v-if="heater || cooler || fan" class="action">
                <span v-class="button-active : mode == 'auto'" v-on="click: modeToggle" class="button-icon glyphicons glyphicons-repeat mode-toggle"></span>
            </div>

            <div v-if="heater" class="action">
                <span v-class="button-active : heater.state" v-on="click: heaterToggle" class="button-icon glyphicons glyphicons-heat device-toggle"></span>
            </div>

            <div v-if="fan" class="action">
                <span v-class="button-active : fan.state" v-on="click: fanToggle" class="button-icon glyphicons glyphicons-snowflake device-toggle"></span>
            </div>

        </div>

    </div>
</script>

</div>


<script src="/js/dashboard.js"></script>

</body>
</html>