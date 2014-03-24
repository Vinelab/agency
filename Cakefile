fs = require 'fs'

{print} = require 'sys'
{exec, spawn} = require 'child_process'

path  = require 'path'

# build = (callback) ->
#   coffee = spawn 'coffee', ['-c', '-o', '.', 'coffee']
#   coffee.stderr.on 'data', (data) ->
#     process.stderr.write data.toString()
#   coffee.stdout.on 'data', (data) ->
#     print data.toString()
#   coffee.on 'exit', (code) ->
#     callback?() if code is 0

watch = (dir, done)-> fs.watch dir, done

concat = (dir, output, done)->
  exec "coffeescript-concat -I #{path.resolve(dir)} -o #{path.resolve(output)}", done

compile = (source, destination)->

  coffee = spawn 'coffee', ['-c', '-o', destination, source]
  coffee.stderr.on 'data', (data) -> process.stderr.write data.toString()
  coffee.stdout.on 'data', (data) -> print data.toString()

task 'public', 'Watch CMS CoffeeScript files for changes', ->

  destination = path.resolve('./public/cms/js/')
  source      = "#{destination}/src"
  conf_dir    = "#{source}/configuration"
  conf_output = "#{source}/Configuration.coffee"

  concat conf_dir, conf_output, (err, stdout, stderr)-> compile source, destination
