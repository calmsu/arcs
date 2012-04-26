formats =
  second: [1, 60]
  minute: [60, 3600]
  hour  : [3600, 604800]
  week  : [604800, 2419200]
  month : [2419200, 31536000]
  year  : [31536000, Number.MAX_VALUE]

arcs.relativeDate = (date) ->
  date = new Date(date) unless date instanceof Date
  delta = ((new Date).getTime() - date.getTime())
  for name, vals of formats
    [threshold, ratio] = vals
    if delta < threshold
      formatted = Math.round(delta/ratio)
      return "#{formatted} #{arcs.inflector.pluralize(name, formatted)} ago"
