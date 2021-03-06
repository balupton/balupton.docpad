###
title: 'Benjamin Lupton'
###

# Prepare
documentTitle = @getPreparedTitle()

# HTML
doctype 5
html lang: 'en', ->
	head ->
		# Standard
		meta charset: 'utf-8'
		meta 'http-equiv': 'X-UA-Compatible', content: 'IE=edge,chrome=1'
		meta 'http-equiv': 'content-type', content: 'text/html; charset=utf-8'
		meta name: 'viewport', content: 'width=device-width, initial-scale=1'
		text  @getBlock('meta').toHTML()

		# Feed
		for feed in @site.feeds
			link
				href: h feed.href
				title: h feed.title
				type: (feed.type or 'application/atom+xml')
				rel: 'alternate'

		# SEO
		title documentTitle
		meta name: 'title', content: documentTitle
		meta name: 'author', content: @getPreparedAuthor()
		meta name: 'email', content: @getPreparedEmail()
		meta name: 'description', content: @getPreparedDescription()
		meta name: 'keywords', content: @getPreparedKeywords()

		# Styles
		text  @getBlock('styles').add(@site.styles).toHTML()
	body ->
		# Heading
		header '.heading', ->
			a href:'/', title:'Return home', ->
				h1 -> @site.text.heading
				span '.heading-avatar', ->
			h2 -> @site.text.subheading

		# Pages
		nav '.pages', ->
			ul ->
				for page in @getCollection('pages').toJSON()
					pageMatch = page.match or page.url
					documentMatch = @document.match or @document.url
					cssname = if documentMatch.indexOf(pageMatch) is 0 then 'active' else 'inactive'
					li 'class':cssname, ->
						a href:page.url.replace(/[/]+$/, '') + '/', title:page.menuTitle, ->
							page.menuText or page.name

		# Document
		article '.page',
			'typeof': 'sioc:page'
			about: @document.url
			datetime: @document.date.toISOString()
			-> @content

		# Footing
		footer '.footing', ->
			div '.about', -> @site.text.about
			div '.copyright', -> @site.text.copyright

		# Sidebar
		aside '.sidebar', ->
			section ".links", ->
				for item in @site.socialLinks
					h1 -> @link(item.code)

		# Scripts
		text @getBlock('scripts').add(@site.scripts).toHTML()

		# Modals
		aside '.modal.referrals.hide', ->
			section ".links", ->
				for item in @site.referralLinks
					h3 -> @link(item.code, item.title)

		aside '.modal.contact.hide', -> @partial('content/contact')

		aside '.modal.backdrop.hide', ->
