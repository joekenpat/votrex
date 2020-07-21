@extends('layouts.app')
@section('title', 'Edit Profile Details')
@section('content')
@if (Auth::user()->is_admin())
<div class="uk-container">
  <div class="content-user">
    <div class="uk-card uk-card-default my-card">
      <form class="uk-form-stacked" method="POST" enctype="multipart/form-data"
        action="{{route('contestant_update_profile')}}">
        <div class="uk-card-header">
          <div class="uk-width-expand">
            <h3 class="uk-card-title uk-margin-remove-bottom"><b style="color: white">Change Profile Details</b></h3>
          </div>
        </div>
        <div class="uk-card-body">
          @csrf
          <div class="uk-grid-small" uk-grid>
            <div class="uk-width-1-1">
              <img class="uk-border-circle uk-align-center contestant_avatar uk-width-1-1"
                src="{{Auth::user()->avatar != null?asset(sprintf("images/users/%s/%s",Auth::user()->id,Auth::user()->avatar)):asset("images/misc/default_avatar.png") }}">
            </div>
            <div class="uk-width-1-2">
              <div class="uk-inline">
                <label class="uk-form-label form-label" for="last_name">Last Name</label>
                <input placeholder="Last Name"
                  class="uk-input @error('last_name') uk-form-danger @enderror uk-form-width-large" type="text"
                  id="last_name" name="last_name" value="{{ old('last_name')?:Auth::User()->last_name }}" autofocus
                  required autocomplete="family-name">
              </div>
              @error('last_name')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="uk-width-1-2">
              <div class="uk-inline">
                <label class="uk-form-label form-label" for="First Name">First Name</label>
                <input placeholder="First name"
                  class="uk-input @error('first_name') uk-form-danger @enderror uk-form-width-large" type="text"
                  id="first_name" name="first_name" value="{{ old('first_name')?:Auth::User()->first_name }}" required
                  autocomplete="given-name">
              </div>
              @error('first_name')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>
            <div class="uk-width-1-2">
              <div class="uk-inline">
                <label class="uk-form-label form-label" for="phone">Phone</label>
                <input placeholder="Phone" class="uk-input @error('phone') uk-form-danger @enderror uk-form-width-large"
                  type="text" id="phone" name="phone" value="{{ old('phone')?:Auth::User()->phone }}" required
                  autocomplete="tel">
              </div>
              @error('phone')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="uk-width-1-2">
              <div class="uk-inline">
                <label class="uk-form-label form-label" for="gender">Gender</label>
                <div class="uk-form-controls">
                  <select class="uk-select @error('gender') uk-form-danger @enderror uk-form-width-large" id="gender"
                    name="gender" autocomplete="sex">
                    <option value="">-- Gender --</option>
                    @foreach (['Male','Female'] as $gender)
                    @if((old('gender') !== null && old('gender') == strtolower($gender)) || Auth::User()->gender ==
                    strtolower($gender))
                    <option selected value="{{strtolower($gender)}}">{{$gender}}</option>
                    @else
                    <option value="{{strtolower($gender)}}">{{$gender}}</option>
                    @endif
                    @endforeach
                  </select>
                </div>
              </div>
              @error('gender')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>
            <div class="uk-width-1-1">
              <div class="uk-width-1-1" uk-form-custom="target: true">
                <label class="uk-form-label form-label" for="avatar">Profile Image</label>
                <input type="file" accept=".jpeg,.gif,.jpg,.png" id="avatar" name="avatar">
                <input class="uk-input @error('last_name') uk-form-danger @enderror uk-width-1-1" type="text"
                  placeholder="Select Profile image" disabled>
              </div>
              @error('avatar')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>
          </div>
        </div>
        <div class="uk-card-footer uk-margin-bottom">
          <div class="uk-animation-toggle" tabindex="0">
            <button type="submit" class="uk-button uk-width-1-1" style="background-color:#3D9FB9;">
              <b>Update</b></button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@else
<div class="uk-container">
  <div class="content-user">
    <div class="uk-card uk-card-default my-card">
      <form class="uk-form-stacked" method="POST" enctype="multipart/form-data"
        action="{{route('contestant_update_profile')}}">
        <div class="uk-card-header">
          <div class="uk-width-expand">
            <h3 class="uk-card-title uk-margin-remove-bottom"><b style="color: white">Change Profile Details</b></h3>
          </div>
        </div>
        <div class="uk-card-body">
          @csrf
          <div class="uk-grid-small" uk-grid>
            <div class="uk-width-1-1">
              <img class="uk-border-circle uk-align-center contestant_avatar uk-width-1-1"
                src="{{Auth::user()->avatar != null?asset(sprintf("images/users/%s/%s",Auth::user()->id,Auth::user()->avatar)):asset("images/misc/default_avatar.png") }}">
            </div>
            <div class="uk-width-1-2">
              <div class="uk-inline">
                <label class="uk-form-label form-label" for="last_name">Last Name</label>
                <input onkeydown="return event.key != 'Enter';" placeholder="Last Name"
                  class="uk-input @error('last_name') uk-form-danger @enderror uk-form-width-large" type="text"
                  id="last_name" name="last_name" value="{{ old('last_name')?:Auth::User()->last_name }}" autofocus
                  required autocomplete="family-name">
              </div>
              @error('last_name')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="uk-width-1-2">
              <div class="uk-inline">
                <label class="uk-form-label form-label" for="First Name">First Name</label>
                <input onkeydown="return event.key != 'Enter';" placeholder="First name"
                  class="uk-input @error('first_name') uk-form-danger @enderror uk-form-width-large" type="text"
                  id="first_name" name="first_name" value="{{ old('first_name')?:Auth::User()->first_name }}" required
                  autocomplete="given-name">
              </div>
              @error('first_name')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="uk-width-1-2">
              <div class="uk-inline">
                <label class="uk-form-label form-label" for="middle_name">Middle Name</label>
                <input onkeydown="return event.key != 'Enter';" placeholder="Middle Name"
                  class="uk-input @error('middle_name') uk-form-danger @enderror uk-form-width-large" type="text"
                  id="middle_name" name="middle_name" value="{{ old('middle_name')?:Auth::User()->middle_name }}"
                  autocomplete="additional-name">
              </div>
              @error('middle_name')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="uk-width-1-2">
              <div class="uk-inline">
                <label class="uk-form-label form-label" for="phone">Phone</label>
                <input onkeydown="return event.key != 'Enter';" placeholder="Phone" class="uk-input @error('phone') uk-form-danger @enderror uk-form-width-large"
                  type="text" id="phone" name="phone" value="{{ old('phone')?:Auth::User()->phone }}" required
                  autocomplete="tel">
              </div>
              @error('phone')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="uk-width-1-2">
              <div class="uk-inline">
                <label class="uk-form-label form-label" for="gender">Gender</label>
                <div class="uk-form-controls">
                  <select onkeydown="return event.key != 'Enter';" class="uk-select @error('gender') uk-form-danger @enderror uk-form-width-large" id="gender"
                    name="gender" autocomplete="sex">
                    <option value="">-- Gender --</option>
                    @foreach (['Male','Female'] as $gender)
                    @if((old('gender') !== null && old('gender') == strtolower($gender)) || Auth::User()->gender ==
                    strtolower($gender))
                    <option selected value="{{strtolower($gender)}}">{{$gender}}</option>
                    @else
                    <option value="{{strtolower($gender)}}">{{$gender}}</option>
                    @endif
                    @endforeach
                  </select>
                </div>
              </div>
              @error('gender')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="uk-width-1-2">
              <div class="uk-inline">
                <label class="uk-form-label form-label" for="age">Age</label>
                <input onkeydown="return event.key != 'Enter';" placeholder="Age" class="uk-input @error('age') uk-form-danger @enderror uk-form-width-large"
                  min="15" max="85" type="number" id="age" name="age" value="{{ old('age')?:Auth::User()->age }}">
              </div>
              @error('age')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>
            <div class="uk-width-1-1">
              <div class="uk-inline">
                <label class="uk-form-label form-label" for="state">State of Origin</label>
                <select onkeydown="return event.key != 'Enter';" class="uk-select @error('state') uk-form-danger @enderror uk-form-width-large" type="text"
                  id="state" name="state">
                  <option value="">-- State of Origin --</option>
                  @foreach (
                  [
                  'Abuja',
                  'Abia',
                  'Adamawa',
                  'Akwa Ibom',
                  'Anambra',
                  'Bauchi',
                  'Bayelsa',
                  'Benue',
                  'Borno',
                  'Cross River',
                  'Delta',
                  'Ebonyi',
                  'Edo',
                  'Ekiti',
                  'Enugu',
                  'Gombe',
                  'Imo',
                  'Jigawa',
                  'Kaduna',
                  'Kano',
                  'Katsina',
                  'Kebbi',
                  'Kogi',
                  'Kwara',
                  'Lagos',
                  'Nassarawa',
                  'Niger',
                  'Ogun',
                  'Ondo',
                  'Osun',
                  'Oyo',
                  'Plateau',
                  'Rivers',
                  'Sokoto',
                  'Taraba',
                  'Yobe',
                  'Zamfara',
                  ] as $state)
                  @if((old('state') !== null && old('state') == strtolower($state)) || Auth::User()->state ==
                  strtolower($state))
                  <option selected value="{{strtolower($state)}}">{{$state}}</option>
                  @else
                  <option value="{{strtolower($state)}}">{{$state}}</option>
                  @endif
                  @endforeach
                </select>
              </div>
              @error('state')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>
            <div class="uk-width-1-1">
              <div class="uk-inline">
                <label class="uk-form-label form-label" for="sch_id">School</label>
                <div class="uk-form-controls">
                  <select onkeydown="return event.key != 'Enter';" class="uk-select @error('sch_id') uk-form-danger @enderror uk-form-width-large" id="sch_id"
                    name="sch_id" onchange="load_sch_level()" value="{{ old('sch_id')?:Auth::User()->sch_id }}">
                    <option value="">-- Select School --</option>
                    @foreach ($schools as $school)
                    @if((old('sch_id') !== null && old('sch_id') == $school->id) || Auth::User()->sch_id == $school->id)
                    <option selected value="{{$school->id}}">{{$school->name}}</option>
                    @else
                    <option value="{{$school->id}}">{{$school->name}}</option>
                    @endif
                    @endforeach
                  </select>
                </div>
              </div>
              @error('sch_id')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>

            <div class="uk-width-1-2">
              <div class="uk-inline">
                <label class="uk-form-label form-label" for="sch_level"> School Level</label>
                <div class="uk-form-controls">
                  <select onkeydown="return event.key != 'Enter';" class="uk-select @error('sch_level') uk-form-danger @enderror  uk-form-width-large"
                    id="sch_level" name="sch_level" value="{{ old('sch_level')?:Auth::User()->sch_level }}">
                    <option value="">-- Select Level --</option>
                    @if (old('sch_level') !== null)
                    <option selected value="{{old('sch_level')}}">{{old('sch_level')}}</option>
                    @elseif( Auth::User()->sch_level !== null)
                    <option selected value="{{Auth::User()->sch_level}}">{{Auth::User()->sch_level}}</option>
                    @endif
                  </select>
                </div>
              </div>
              @error('sch_level')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>
            <div class="uk-width-1-2">
              <div class="uk-inline">
                <label class="uk-form-label form-label" for="sch_faculty">Faculty</label>
                <input onkeydown="return event.key != 'Enter';" placeholder="Faculty"
                  class="uk-input @error('sch_faculty') uk-form-danger @enderror uk-form-width-large" type="text"
                  id="sch_faculty" name="sch_faculty" value="{{ old('sch_faculty')?:Auth::User()->sch_faculty }}">
              </div>
              @error('sch_faculty')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>
            <div class="uk-width-1-1">
              <div class="uk-width-1-1" uk-form-custom="target: true">
                <label class="uk-form-label form-label" for="form-stacked-text ">Profile Image</label>
                <input onkeydown="return event.key != 'Enter';" type="file" accept=".jpeg,.gif,.jpg,.png" id="avatar" name="avatar">
                <input onkeydown="return event.key != 'Enter';" class="uk-input @error('last_name') uk-form-danger @enderror uk-width-1-1" type="text"
                  placeholder="Select Profile image" disabled>
              </div>
              @error('avatar')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>
            <div class="uk-width-1-1">
              <div class="uk-width-1-1" uk-form-custom="target: true">
                <label class="uk-form-label form-label" for="form-stacked-text ">Select your Media Images (5)
                  Max</label>
                <input type="file" onchange="val_file_count(this)" multiple accept=".jpeg,.gif,.jpg,.png" id="media" name="media[]">
                <input onkeydown="return event.key != 'Enter';" class="uk-input @error('last_name') uk-form-danger @enderror uk-width-1-1" type="text"
                  placeholder="Select Media image" multiple accept=".jpeg,.gif,.jpg,.png" disabled>
              </div>
              @error('media')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>
            <div class="uk-width-1-1">
              <div class="uk-inline">
                <label class="uk-form-label form-label" for="bio">Bio (5000 Character Max)</label>
              <textarea placeholder="Bio" rows="6" onkeyup="count_bio(this)"
                  class="uk-textarea @error('bio') uk-form-danger @enderror uk-form-width-large" id="bio" name="bio"
                  required>{{ old('bio') != null?old('bio'):Auth::User()->bio }}</textarea>
              </div>
              <span class="uk-label uk-label-warning" id="bio_counter">
                Characters left: {{5000 - strlen(Auth::user()->bio)}}
              </span>
              @error('bio')
              <span class="uk-text-danger">{{ $message }}</span>
              @enderror
            </div>

          </div>
        </div>
        <div class="uk-card-footer uk-margin-bottom">
          <div class="uk-animation-toggle" tabindex="0">
            <button type="submit" class="uk-button uk-width-1-1" style="background-color:#3D9FB9;">
              <b>Update</b></button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endif
@endsection
@push('bottom_scripts')
<script>
  function load_sch_level(){
    let sch_data  = @json($schools);
    var sch_id = document.getElementById('sch_id').value
    let sch_level = document.getElementById('sch_level')
    sch_level.length = 1
    sch_data.forEach(schs => {
      if(schs.id == sch_id){
        if(schs.type == 'polytechnic'){
          ['ND1','ND2','HND1','HND2'].forEach(level=>{
            ['100','200','300','400','500','600']
            var option = document.createElement("option");
            option.text = level;
            option.value= level;
            sch_level.add(option);
          })
        }else{
          ['100','200','300','400','500','600'].forEach(level=>{
            var option = document.createElement("option");
            option.text = level;
            option.value= level;
            sch_level.add(option);
          })
        }
      }
    });
  }

  function val_file_count(media_selector){
    if(media_selector.files.length > 5){
      media_selector.value=''
      UIkit.modal.alert('Maximum Of 5 images allowed,Please Kindly reselect again.');
      media_selector.preventDefault();
    }
  }
  
  function count_bio(bio){
  document.getElementById('bio_counter').innerHTML = "Characters left: " + (5000 - bio.value.length);
};
</script>
@endpush
