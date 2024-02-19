@php
    echo '<?xml version="1.0" encoding="UTF-8" ?>';
    $poster='';
@endphp
<rss version="2.0"
     xmlns:atom="http://www.w3.org/2005/Atom"
     xmlns:content="http://purl.org/rss/1.0/modules/content/"
     xmlns:media="http://search.yahoo.com/mrss/">
    <channel>
        <title>{{ config('other.title') }}: {{ $rss->name }}</title>
        <link>{{ config('app.url') }}</link>
        <description>
            {!! __('This feed contains your secure rsskey, please do not share with anyone.') !!}
        </description>
        <atom:link href="{{ route('rss.show.rsskey', ['id' => $rss->id, 'rsskey' => $user->rsskey]) }}"
                   type="application/rss+xml" rel="self"></atom:link>
        <copyright>{{ config('other.title') }} {{ now()->year }}</copyright>
        <language>en-us</language>
        <lastBuildDate>{{ now()->toRssString() }}</lastBuildDate>
        <ttl>5</ttl>
        @if($torrents)
            @foreach($torrents as $data)
            	@php
                        $meta = match (true) {
                            $data->category->tv_meta => App\Models\Tv::query()
                                ->with('genres', 'networks', 'seasons')
                                ->find($data->tmdb ?? 0),
                            $data->category->movie_meta => App\Models\Movie::query()
                                ->with('genres', 'companies', 'collection')
                                ->find($data->tmdb ?? 0),                            
                            default => null,
                        };
                 @endphp
                <item>
                    <title>{{ $data->name }}</title>                    
                    <category>{{ $data->category->name }}</category>
                    <type>{{ $data->type->name }}</type>
		    <resolution>{{ $data->resolution->name ?? 'No Res' }}</resolution>
	   	    <size>{{ $data->getSize() }}</size>
		    <imdb>
			@if (($data->category->movie_meta || $data->category->tv_meta) && $data->imdb != 0)
				https://anon.to?http://www.imdb.com/title/tt{{ $data->imdb }};                        
                        @endif
		     </imdb>
                     <poster>
			@php
				$poster='https://upload.wikimedia.org/wikipedia/commons/b/b9/No_Cover.jpg';
				if ($data->category->movie_meta && $data->tmdb != 0)
					$poster = tmdb_image('poster_big', $meta->poster);
				if ($data->category->tv_meta && $data->tmdb != 0) 
                                	$poster = tmdb_image('poster_big', $meta->poster); 
				if ($data->category->no_meta)
					if(file_exists(public_path().'/files/img/torrent-cover_'.$data->id.'.jpg'))
						$poster = url('files/img/torrent-cover_' . $data->id . '.jpg');

			@endphp
			{{$poster}}
		     </poster>
		     <tmdb>
				@if ($data->category->movie_meta && $data->tmdb != 0)
					https://anon.to?https://www.themoviedb.org/movie/{{ $data->tmdb }}
                        	@elseif ($data->category->tv_meta && $data->tmdb != 0)
					https://anon.to?https://www.themoviedb.org/tv/{{ $data->tmdb }}                        	
                        	@endif
		    </tmdb>
		    <link>{{ route('torrents.show', ['id' => $data->id ]) }}</link>                    
                    <guid>{{ $data->id }}</guid>
                    <description>
                    	{{$poster}} 
		    	Categoria: {{ $data->category->name }}		    
		    	Tipo: {{ $data->type->name }}
		    	Resolucion: {{ $data->resolution->name ?? 'No Res' }}
		    	Tama&#328;o: {{ $data->getSize() }}
 		    	@if (($data->category->movie_meta || $data->category->tv_meta)  && $data->imdb != 0)
		    		[![](https://icons.iconarchive.com/icons/danleech/simple/48/imdb-icon.png)](http://www.imdb.com/title/tt{{ $data->imdb }})
 		     	@endif
		     	@if ($data->category->movie_meta && $data->tmdb != 0)
				[![](https://i.ibb.co/q0LP1H6/tmdbsmall.png)](https://www.themoviedb.org/movie/{{ $data->tmdb }})
                        @elseif ($data->category->tv_meta && $data->tmdb != 0)
				[![](https://i.ibb.co/q0LP1H6/tmdbsmall.png)](https://www.themoviedb.org/tv/{{ $data->tmdb }}) 
			@endif
		   </description>
                    <dc:creator xmlns:dc="http://purl.org/dc/elements/1.1/">
                        @if(!$data->anon && $data->user)
                            {{ __('torrent.uploaded-by') }} {{ $data->user->username }}
                        @else
                            {{ __('common.anonymous') }} {{ __('torrent.uploader') }}
                        @endif
                    </dc:creator>
                    <pubDate>{{ $data->created_at->toRssString() }}</pubDate>
                    <enclosure
                            url="{{ route('torrent.download.rsskey', ['id' => $data->id, 'rsskey' => $user->rsskey ]) }}"
                            type="application/x-bittorrent"
                            length="39399"
                    />
                </item>
                </item>
            @endforeach
        @endif
    </channel>
</rss>
