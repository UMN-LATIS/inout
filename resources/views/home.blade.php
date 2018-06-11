@extends('base')

@section('content')
<div class="container">
	<div class="jumbotron">
		<h1 class="display-4">Welcome to inout.cla.umn.edu</h1>
		<p class="lead">The inout app provides an easy-to-use online "In/out" board for your unit.  Features like Slack integration make it useful even to web-averse members of your team..</p>
		<hr class="my-4">
		<p>If you think you'd like a board for your own unit, {{ HTML::mailto('mcfa0086@umn.edu', 'let us know') }}.  Or check out our sample board below.</p>
		<p class="lead">
			<a class="btn btn-primary btn-lg" href="/sample" role="button">Sample Board</a>
		</p>
	</div>
</div>
@endsection