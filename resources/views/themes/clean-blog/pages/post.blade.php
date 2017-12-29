@extends('themes.clean-blog.layouts.default')

@section('header')
    <header class="intro-header" style="background-image: url({{ $post->image_url or 'http://lorempixel.com/400/200'}})">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    <div class="post-heading">
                        <h1>{{$post->title}}</h1>
                        <!-- <h2 class="subheading">{{$post->description}}</h2> -->
                        <span class="meta">Posted by <a href="#">{{$post->owner->name}}</a> {{$post->moderated_at->diffForHumans()}}</span>
                    </div>
                </div>
            </div>
        </div>
    </header>

@endsection

@section('content')
    <!-- Post Content -->
    <article>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 col-md-10 col-md-offset-1">
                    {!! ParsedownExtra::instance()->parse($post->content) !!}
                </div>
            </div>
        </div>
        <div id="disqus_thread"></div>
        <script>
            /**
            *  RECOMMENDED CONFIGURATION VARIABLES: EDIT AND UNCOMMENT THE SECTION BELOW TO INSERT DYNAMIC VALUES FROM YOUR PLATFORM OR CMS.
            *  LEARN WHY DEFINING THESE VARIABLES IS IMPORTANT: https://disqus.com/admin/universalcode/#configuration-variables*/
            var disqus_config = function () {
                this.page.url = "{{Request::url()}}";  // Replace PAGE_URL with your page's canonical URL variable
                this.page.identifier = {{$post->id}}; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
            };

            (function() { // DON'T EDIT BELOW THIS LINE
                var d = document, s = d.createElement('script');
                s.src = 'https://tom-jamon.disqus.com/embed.js';
                s.setAttribute('data-timestamp', +new Date());
                (d.head || d.body).appendChild(s);
            })();
        </script>
        <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
    </article>
    @endsection
