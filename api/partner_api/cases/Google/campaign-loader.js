function providePlugin(pluginName, pluginConstructor) {
    var ga = window[window['GoogleAnalyticsObject'] || 'ga'];
    if (ga) ga('provide', pluginName, pluginConstructor);
}

/**
 * Constructor for the campaignLoader plugin.
 */
var CampaignLoader = function(tracker, config) {
    this.tracker = tracker;
    this.nameParam = config.nameParam || 'name';
    this.sourceParam = config.sourceParam || 'source';
    this.mediumParam = config.mediumParam || 'medium';
    this.isDebug = config.debug;
};

/**
 * Loads campaign fields from the URL and updates the tracker.
 */
CampaignLoader.prototype.loadCampaignFields = function() {
    this.debugMessage('Loading custom campaign parameters');

    var nameValue = getUrlParam(this.nameParam);
    if (nameValue) {
        this.tracker.set('campaignName', nameValue);
        this.debugMessage('Loaded campaign name: ' + nameValue);
    }

    var sourceValue = getUrlParam(this.sourceParam);
    if (sourceValue) {
        this.tracker.set('campaignSource', sourceValue);
        this.debugMessage('Loaded campaign source: ' + sourceValue);
    }

    var mediumValue = getUrlParam(this.mediumParam);
    if (mediumValue) {
        this.tracker.set('campaignMedium', mediumValue);
        this.debugMessage('Loaded campaign medium: ' + mediumValue);
    }
};

/**
 * Enables / disables debug output.
 */
CampaignLoader.prototype.setDebug = function(enabled) {
    this.isDebug = enabled;
};

/**
 * Displays a debug message in the console, if debugging is enabled.
 */
CampaignLoader.prototype.debugMessage = function(message) {
    if (!this.isDebug) return;
    if (console) console.debug(message);
};

/**
 * Utility function to extract a URL parameter value.
 */
function getUrlParam(param) {
    var match = document.location.search.match('(?:\\?|&)' + param + '=([^&#]*)');
    return (match && match.length == 2) ? decodeURIComponent(match[1]) : '';
}

// Register the plugin.
providePlugin('campaignLoader', CampaignLoader);
