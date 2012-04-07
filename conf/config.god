WORKERS = 3
ARCS_ROOT = '/home/reyno321/public_html/arcs'
CMD = "./app/Console/cake worker -s"

(1..WORKERS).each do |n|
  God.watch do |w|
    w.dir = ARCS_ROOT
    w.group = 'arcs-workers'
    w.name = "worker-#{n}"
    w.start = CMD + " -l #{w.name}"
    w.log = '/var/log/arcs-worker.log'
    w.keepalive
  end
end
