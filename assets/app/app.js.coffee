#
#= require /lib/jquery-1.10.2.js
#= require /lib/handlebars-1.1.2.js
#= require /lib/ember-1.5.0.js
#= require /lib/ember-data.min.js
#= require templates/application.hbs
#= require templates/index.hbs
#= require templates/about.hbs
#
App = Ember.Application.create()

App.Router.map ->
  @.route 'about'

App.IndexController = Ember.Controller.extend
  name: 'Zachary'
  class: 'hero'
  time: (->
    (new Date()).toDateString()
  ).property()
