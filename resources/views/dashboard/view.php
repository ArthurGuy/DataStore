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
                    Loading weather forecast...
                </div>
            </div>

            <div v-if="forecastAvailable">

                <h1 class="daySummary" v-text="forecast.dayWeather.daySummary"></h1>

                <div class="keyForecast">

                    <div class="col">
                        <weather-icon width="150" height="150" icon="{{ forecast.futureForecast.icon }}"></weather-icon>
                    </div>

                    <div class="col">
                        <span class="primaryTemp"><temperature value="{{ forecast.temperature }}"></temperature></span>

                        <span class="tempRange"><temperature value="{{ forecast.dayWeather.dayMinTemperature }}"></temperature> - <temperature value="{{ forecast.dayWeather.dayMaxTemperature }}"></temperature></span>
                    </div>

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

        <!--
        <div v-class="refreshing : loading" class="refresh">
            <span class="button-icon glyphicons glyphicons-refresh" v-on="click: refreshData"></span>
        </div>
        -->

        <small>
            Local: <?php echo json_decode(file_get_contents(base_path('resources/assets/versions.json')), true)['dashboard']; ?> |
            Remote: <span>{{ meta.version }}</span> |
            Last Update: <span>{{ lastDataUpdate | simple-date }}</span>
            <br />
            Forecast: <span v-if="localLocation">Local</span><span v-if="!localLocation">Home</span> |
            <span class="glyphicons glyphicons-riflescope" v-on="click: fetchCordinates"></span>
            <span>{{ latitude }}</span>
            <span>{{ longitude }}</span>
        </small>

        <div class="msg-container" v-class="show: showMessage" v-class="app-loaded: appLoaded">
            <div class="msg">{{ messageText }}</div>
        </div>

    </div>

<script id="room-template" type="x-template">
    <div v-class="heater-on : heater && heater.on, cooling-on : fan && fan.on, auto-mode : mode == 'auto'" class="room">

        <div class="heading">

            <span class="name">
                {{ name }}
                <span v-if="hasWarning" class="glyphicons glyphicons-warning-sign" title="No Updates since {{ last_updated }}" data-toggle="tooltip" data-placement="top" style="color:#FF7100;font-size: 16px;padding-top: 5px;"></span>
            </span>
            <span class="primaryTemp"><temperature value="{{ temperature }}"></temperature> | {{ humidity }}%</span>

        </div>

        <span class="condition hidden">{{ condition }}</span>

        <span class="heater-status">
            <span v-if="home">Maintaining <temperature value="{{ target_temperature }}"></temperature></span>
            <span v-if="!home">Maintaining Away <temperature value="{{ away_temperature }}"></temperature></span>
        </span>

        <div class="action-row">

            <div v-if="heater || cooler || fan" class="action">
                <span v-class="button-active : mode == 'auto'" v-on="click: modeToggle" class="button-icon glyphicons glyphicons-repeat mode-toggle"></span>
            </div>

            <div v-if="heater" class="action">
                <span v-class="button-active : heater.on" v-on="click: heaterToggle" class="button-icon glyphicons glyphicons-heat device-toggle"></span>
            </div>

            <div v-if="fan" class="action">
                <span v-class="button-active : fan.on" v-on="click: fanToggle" class="button-icon glyphicons glyphicons-snowflake device-toggle"></span>
            </div>

            <div v-if="lighting" class="action">
                <span v-class="button-active : lighting.on" v-on="click: lightingToggle" class="button-icon glyphicons glyphicons-lightbulb device-toggle"></span>
            </div>

        </div>

        <div class="action-list">

            <div v-if="heater || cooler || fan" class="action">
                <span v-class="button-active : mode == 'auto'" v-on="click: modeToggle" class="button-icon glyphicons glyphicons-repeat mode-toggle"></span>
                <span class="action-label">Auto</span>
                <span class="right">
                    <span v-on="click: autoControlToggle" class="glyphicons" v-class="glyphicons-chevron-down: !autoShowControl, glyphicons-chevron-up: autoShowControl"></span>
                </span>
                <div class="action-controls" v-if="autoShowControl">

                </div>
            </div>

            <div v-if="heater" class="action">
                <span v-class="button-active : heater.on" v-on="click: heaterToggle" class="button-icon glyphicons glyphicons-heat device-toggle"></span>
                <span class="action-label">{{ heater.name }}</span>
                <span class="right">
                    <span v-on="click: heatingControlToggle" class="glyphicons" v-class="glyphicons-chevron-down: !heatingShowControl, glyphicons-chevron-up: heatingShowControl"></span>
                </span>
                <div class="action-controls" v-if="heatingShowControl">

                </div>
            </div>

            <div v-if="fan" class="action">
                <span v-class="button-active : fan.on" v-on="click: fanToggle" class="button-icon glyphicons glyphicons-snowflake device-toggle"></span>
                <span class="action-label">{{ fan.name }}</span>
                <span class="right">
                    <span v-on="click: fanControlToggle" class="glyphicons" v-class="glyphicons-chevron-down: !fanShowControl, glyphicons-chevron-up: fanShowControl"></span>
                </span>
                <div class="action-controls" v-if="fanShowControl">

                </div>
            </div>

            <div v-if="lighting" class="action">
                <span v-class="button-active : lighting.on" v-on="click: lightingToggle" class="button-icon glyphicons glyphicons-lightbulb device-toggle"></span>
                <span class="action-label">{{ lighting.name }}</span>
                <colour-patch v-if="lighting && lighting.on" raw-colour="{{lighting.value}}"></colour-patch>
                <span class="right">
                    <span v-on="click: lightingControlToggle" class="glyphicons" v-class="glyphicons-chevron-down: !lightingShowControl, glyphicons-chevron-up: lightingShowControl"></span>
                </span>
                <div class="action-controls" v-if="lightingShowControl">
                    <colour name="light-color" on-update="{{updateLightingColour}}" raw-colour="{{lighting.value}}"></colour>
                </div>
            </div>

        </div>

        <!--
        <div class="action-detail" v-if="lighting && lighting.on">
            <span>{{ lighting.name }}</span>
            <colour-patch raw-colour="{{lighting.value}}"></colour-patch>
            <div style="float:right">
                <span v-if="!lightingShowControl" v-on="click: lightingControlToggle" class="glyphicons glyphicons-chevron-down"></span>
                <span v-if="lightingShowControl" v-on="click: lightingControlToggle" class="glyphicons glyphicons-chevron-up"></span>
            </div>
            <div class="ligting-control" v-if="lightingShowControl">
                <colour name="light-color" on-update="{{updateLightingColour}}" raw-colour="{{lighting.value}}"></colour>
            </div>
        </div>
        -->
    </div>
</script>

</div>


<script src="/js/dashboard.js"></script>

</body>
</html>