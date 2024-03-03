@foreach ($blocks as $block)
	{{ $block->render() }}
@endforeach
