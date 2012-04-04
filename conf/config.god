WORKERS = 2
CMD = File.dirname(__FILE__) + '/../bin/worker -s'

(1..WORKERS).each do |n|
  God.watch do |w|
    w.group = 'arcs-workers'
    w.name = "arcs-worker-#{n}"
    w.start = CMD
    w.keepalive = true
  end
end
