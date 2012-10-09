(function() {

  arcs.config = {
    metadata: {
      'copyright': 'Who owns copyright on this resource?',
      'coverage': 'What area(s) does this resource concern',
      'creator': 'Person who made the physical resource',
      'date': 'When was the original resource created?',
      'date-modified': 'What is the most recent date the resource was modified?',
      'description': 'Short description of the resource',
      'format': 'What kind of file is this resource?',
      'identifier': 'Dig-specific title for the resource (e.g. Identifier for ' + 'a photo: 72-BW-01-63_64)',
      'language': 'What is the original language of this resource?',
      'location': 'Area of the dig this resource concerns',
      'medium': 'What is this resource made of? (e.g: Notebook page: white ' + 'paper and ink)',
      'subject': 'Subjects are like tags or keywords...short, basic descriptions ' + 'about things what a resource describes'
    },
    metadataSingular: ['identifier'],
    types: {
      '': '',
      'Drawing': 'Drawings are informational line drawings of artifacts, ' + 'places, or other objects',
      'Inventory Card': 'Notecard dedicated to a single artifact',
      'Map': 'There are two types of map, 1) Vectored CAD drawings with ' + 'geo coordinates and 2) hand-drawn renderings of dig areas ' + 'and trenches',
      'Notebook': 'Archaeologist field notes collected in small leather-bound ' + 'notebooks...a Notebook is a collection of Notebook Pages',
      'Notebook Page': 'A two-page spread of field notes from a Notebook',
      'Photograph': 'A photographic negative either depicting a significant scene ' + 'or object',
      'Report': 'Weekly or monthly typed account of the happenings of the dig'
    },
    flags: {
      'incorrect': 'Incorrect attributes',
      'spam': 'Spam',
      'duplicate': 'Duplicate',
      'other': 'Other'
    }
  };

}).call(this);
