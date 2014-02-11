#= require Sections
#= require Roles
#= require Permissions

class Configuration extends Sections

    constructor: ->
        @Sections    = new Sections
        @Roles       = new Roles
        @Permissions = new Permissions

$ -> window.Configuration = new Configuration