# config.god
# ----------
# This is an example configuration for managing ARCS workers with 
# the God process manager.

WORKERS = 2
GROUP = 'arcs'
ROOT = '/var/cakedev/arcs'
CMD = './app/Console/cake worker -s'

(1..WORKERS).each do |n|
  God.watch do |w|
    w.uid = 'www-data'
    w.dir = ROOT
    w.group = GROUP
    w.name = "#{GROUP}-worker-#{n}"
    w.start = CMD + " -l #{w.name}"
    w.log = "#{ROOT}/app/tmp/logs/worker.log"
    w.keepalive
  end
end
