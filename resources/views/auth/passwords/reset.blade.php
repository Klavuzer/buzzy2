@extends('app')

@section('content')
<div class="wt-container">
	<div class="global-container container">
		<div class="content" style="float:none;margin:100px auto;width:500px">
        	@include("_particles.auth.reset-password")
		</div>
	</div>
</div>
@endsection
