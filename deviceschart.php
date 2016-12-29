<style>
body {
  background-color: #1D1F20;
}
.vertical {
  display: inline-block;
  width: 20%;
  height: 40px;
  -webkit-transform: rotate(-90deg); /* Chrome, Safari, Opera */
  transform: rotate(-90deg);
}
.vertical {
  box-shadow: inset 0px 4px 6px #ccc;
}
.progress-bar {
  box-shadow: inset 0px 4px 6px rgba(100,100,100,0.6);
}
</style> 
 
 
<div class="progress vertical">
  <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 40%;">
  </div>  
</div>