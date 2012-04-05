WORKERS = 2
ARCS_ROOT = '/var/cakedev/arcs'
CMD = "/bin/bash -l -c 'cd #{ARCS_ROOT} && ./bin/worker -s'"

(1..WORKERS).each do |n|
  God.watch do |w|
    w.group = 'arcs-workers'
    w.name = "arcs-worker-#{n}"
    w.start = CMD
    w.log = '/var/log/arcs-worker.log'
    w.keepalive
  end
end
