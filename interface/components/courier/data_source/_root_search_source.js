define(function (require) {
  return function RootSearchSource(Private, $rootScope, config, Promise, indexPatterns, timefilter, Notifier) {
    var SearchSource = Private(require('components/courier/data_source/search_source'));
    var Manager = Private(require('components/courier/data_source/_root_search_source_manager'));

    var notify = new Notifier({ location: 'Root Search Source' });

    var globalSource = new SearchSource();
    globalSource.inherits(false); // this is the final source, it has no parents
    globalSource.filter(function (globalSource) {
      // dynamic time filter will be called in the _flatten phase of things
      return timefilter.get(globalSource.get('index'));
    });

    var appSource; // set in setAppSource()
    resetAppSource();

    /**
     * Get the default index from the config, and hook it up to the globalSource.
     *
     * @return {Promise}
     */
    function loadDefaultPattern() {
      return notify.event('loading default Dataset', function () {
        var defId = config.get('defaultIndex');

        return Promise.cast(defId && indexPatterns.get(defId))
        .then(function (pattern) {
          pattern = pattern || undefined;
          globalSource.set('index', pattern);
          notify.log('Current Dataset set to', defId);
        });
      });
    }

    // when the route changes, clear the appSource
    $rootScope.$on('$routeChangeStart', resetAppSource);

    /**
     * Get the current AppSource
     * @return {Promise} - resolved with the current AppSource
     */
    function getAppSource() {
      return appSource;
    }

    /**
     * Set the current AppSource
     * @param {SearchSource} source - The Source that represents the applications "root" search source object
     */
    function setAppSource(source) {
      appSource = source;
      Manager.set(source);

      // walk the parent chain until we get to the global source or nothing
      // that's where we will attach to the globalSource
      var literalRoot = source;
      while (literalRoot._parent && literalRoot._parent !== globalSource) {
        literalRoot = literalRoot._parent;
      }

      literalRoot.inherits(globalSource);
    }



    /**
     * Sets the appSource to be a new, empty, SearchSource
     * @return {undefined}
     */
    function resetAppSource() {
      setAppSource(new SearchSource());
    }

    return {
      get: getAppSource,
      set: setAppSource,
      loadDefault: loadDefaultPattern
    };
  };
});
