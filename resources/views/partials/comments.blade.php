@foreach ($lead->comments as $comment)
    <li>
        <i class="fas" :class="type"></i>
        <div class="timeline-item">
            <div class="timeline-body">
                <div class="comment-header d-flex justify-content-between border-bottom mb-2 border-primary">
                    <small>{{$comment->reason}} </small>  <small>{{$comment->user->name}}</small>
                </div>
                {{$comment->body}}
                <div class="comment-footer d-flex mt-1 {{!empty($comment->date) ? 'justify-content-between' : 'justify-content-end'}}">
                    @if(!empty($comment->date))
                    <small>
                        {{$comment->date->format('d.m.Y')}}
                    </small>
                    @endif
                    <small>
                        <i class="fa fa-clock"></i>{{$comment->created_at->diffForHumans()}}
                    </small>
                </div>
            </div>
        </div>
    </li>
@endforeach