@extends('layouts.app')

@section('content')
@if(Auth::user()->is_admin())
<div class="uk-container">
  <div class="uk-card uk-card-default">
    <div class="uk-container">
      <table class="uk-table uk-table-responsive uk-table-divider">
        <thead>
          <tr>
            <th>#</th>
            <th>NAME</th>
            <th>EMAIL</th>
            <th>CONTESTANT</th>
            <th>QUANTITY</th>
            <th>AMOUNT</th>
            <th>CONTEST</th>
            <th>DATE</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($votes as $vote)
          <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$vote->first_name}} {{$vote->last_name}}</td>
            <td>{{$vote->email}}</td>
            <td>{{$vote->contestant->get_full_name()}}</td>
            <td>{{$vote->quantity}}</td>
            <td>{{$vote->amount}}</td>
            <td>{{$vote->contest->title}}</td>
            <td>{{$vote->created_at}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>


    @if ($votes->hasPages())
    <div class="uk-text-center uk-card-footer">
      {!! $votes->links() !!}
    </div>
    @endif

  </div>
</div>
@else
<div class="uk-container uk-margin-top">
  <div class="uk-grid-small uk-child-width-1-1 uk-child-width-1-2@s" uk-grid>
    @foreach ($votes as $vote)
    <div>
      <div class="uk-card uk-card-default my-card">
        <div class="uk-card-header">
          <div class="uk-width-expand">
          <h3 class="uk-card-title uk-margin-remove-bottom"><b style="color: white">Vote # {{$loop->iteration * $votes->currentPage()}}</b></h3>
          </div>
        </div>
        <div class="uk-card-body">
          <ul class="contestant-details uk-padding-remove-left">
            <li><b>FIRST NAME: </b>{{$vote->first_name}}</li>
            <li><b>LAST NAME: </b>{{$vote->last_name}}</li>
            <li><b>EMAIL: </b>{{$vote->email}}</li>
            <li><b>QUANTITY: </b>{{$vote->quantity}}</li>
            <li><b>CONTEST: </b>{{$vote->contest->title}}</li>
            <li><b>TIME: </b>{{$vote->created_at}}</li>
          </ul>
        </div>
      </div>

    </div>
      @endforeach
  </div>
</div>
<div class="uk-width1-1">
  <!--=======================-->
  @if ($votes->hasPages())
  <div class="uk-text-center">
    {!! $votes->links() !!}
  </div>
  @endif
</div>
</div>
</div>
@endif
@endsection
