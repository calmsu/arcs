WORKERS = 2
GROUP = 'arcsdev'
ROOT = '/home/reyno321/public_html/arcs'
CMD = './app/Console/cake worker -s'

(1..WORKERS).each do |n|
  God.watch do |w|
    w.uid = 'www-data'
    w.dir = ROOT
    w.group = GROUP
    w.name = "arcsdev-worker-#{n}"
    w.start = CMD + " -l #{w.name}"
    w.log = "#{ROOT}/app/tmp/logs/worker.log"
    w.keepalive
  end
end
