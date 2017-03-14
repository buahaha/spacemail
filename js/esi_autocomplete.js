// Instantiate the Bloodhound suggestion engine
var esisearch = new Bloodhound({
  datumTokenizer: function(datum) {
    return Bloodhound.tokenizers.whitespace(datum.name);
  },
  queryTokenizer: Bloodhound.tokenizers.whitespace,
  sufficient: 40,
  remote: {
    wildcard: '%QUERY',
    url: 'esimailsearch.php?q=%QUERY',
    transform: function(response) {
      // Map the remote source JSON array to a JavaScript object array
      return $.map(response, function(esisearch) {
          return {
            name: esisearch.name,
            id: esisearch.id,
            category: esisearch.category,
          };
      });
    }
  },
});


// Instantiate the Typeahead UI
$('.typeahead').typeahead(null, {
  display: 'name',
  limit: 50,
  source: esisearch,
  templates: {
  suggestion: function(data) {
      if(data.category == 'character') {
         return '<div class="tt-sug-div"><img class="tt-sug-img img-rounded" src="https://imageserver.eveonline.com/Character/'+data.id+'_32.jpg"><p class="tt-sug-text">'+data.name+'</p></div>';
      } else if (data.category == 'corporation') {
         return '<div class="tt-sug-div"><img class="tt-sug-img img-rounded" src="https://imageserver.eveonline.com/Corporation/'+data.id+'_32.png"><p class="tt-sug-text">'+data.name+'</p></div>';
      } else if (data.category == 'alliance') {
         return '<div class="tt-sug-div"><img class="tt-sug-img img-rounded" src="https://imageserver.eveonline.com/Alliance/'+data.id+'_32.png"><p class="tt-sug-text">'+data.name+'</p></div>';
      }
    }
  }
}).bind('typeahead:render', function(e) {
    $('.typeahead').parent().find('.tt-selectable:first').addClass('tt-cursor');
}).bind('typeahead:select', function(ev, suggestion) {
    $('#inv-button').removeClass('disabled');
    $('#inv-id').val(suggestion.id);
}).on('keyup', function(e) {
    if(e.which != 13) {
      $('#inv-button').addClass('disabled');
      $('#inv-id').val('');
    }
});
