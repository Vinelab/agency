#= require Sections
#= require Roles
#= require Permissions

class Configuration extends Sections

    constructor: ->
        @Sections    = new Sections
        @Roles       = new Roles
        @Permissions = new Permissions
        @Applications = new Applications

$ -> window.Configuration = new Configuration