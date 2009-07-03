BOSSTERMS = function() {

    var config = {

        appID: 'TX6b4XHV34EnPXW0sYEr51hP1pn5O8KAGs.LQSXer1Z7RmmVrZouz5SvyXkWsVk-'
    };

    var filteredterms = {};

    var query;

    var out = {};

    var cb;

    function getTerms(term,callback){

        query = term;

        var api = 'http://boss.yahooapis.com/ysearch/web/v1/' + term + '?format=json&view=keyterms&callback=BOSSTERMS.seed&appid='+config.appID;

        var script = document.createElement('script');

            script.setAttribute('type','text/javascript');

            script.setAttribute('src',api);

            document.getElementsByTagName('head')[0].appendChild(script);

        if(typeof callback === 'function') {

            cb = callback;    
             
        }

    };

    function seed(o) {

        query = getQuery(o); 

        out.term = query;

        out.keywords = [];

        var terms = o.ysearchresponse.resultset_web;

            for(var i=0;i<terms.length;i++) {
 
                if(typeof terms[i].keyterms.terms !== 'undefined') {   
               
                    filter(terms[i].keyterms.terms);
                }
            }   

            for(var i in filteredterms) {

                    out.keywords.push(filteredterms[i]);
            }

            out.html = '<ul><li>' + out.keywords.join("</li><li>") + '</li></ul>';

            cb(out);
    };

    function filter(o) {

             for(var i=0;i<o.length;i++) {

                     if(o[i] !== query) {

                          filteredterms[o[i]] = o[i];
                     }
             } 
    };   

    function getQuery(o) {

             if(typeof o.ysearchresponse.nextpage !== 'undefined') {

                       var next = o.ysearchresponse.nextpage.split("?")[0];

                       var query = next.replace(/.*?\//g,'');            
             }

             if(typeof o.ysearchresponse.prevpage !== 'undefined') {

                       var prev = o.ysearchresponse.prevpage.split("?")[0];

                       var query = prev.replace(/.*?\//g,'');            
             }

        return query;
    };

    return {get: getTerms,config: config,seed: seed}

}();