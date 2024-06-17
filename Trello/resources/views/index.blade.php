<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Trello</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app" data-app>
    <div>
    <div class="block">
      <div class="to-do" style="display: grid height: 20px;">To Do
        <div>
          <div>
            <modalshow-component
            :column="'To Do'"
            ></modalshow-component>
          </div>
        </div>
      </div>

      <div class="in-progress" style="display: grid height: 20px;">In Progress
        <div>
          <div>
            <modalshow-component
            :column="'In Progress'"
            ></modalshow-component>
          </div>
        </div>
      </div>
      <div class="done" style="display: grid height: 20px;">Done
        <div>
          <div>
            <modalshow-component
            :column="'Done'"
            ></modalshow-component>
          </div>

        </div>
      </div>
    </div>

  </div>
 </div>
    <script src="{{ mix('js/app.js') }}"></script>
    
</body>
</html>
<style>
.block {
  display: flex;
  gap: 50px;
  justify-content: center;
  margin-top: 20px;
}
</style>
