@if(isset($companySetting) && $companySetting && $companySetting->topbar_enabled && $companySetting->topbar_message)
<div class="topbar">
  {!! $companySetting->topbar_message !!}
  @if($companySetting->topbar_link)
    &nbsp;|&nbsp; <a href="{{ $companySetting->topbar_link }}">Shop Now ›</a>
  @endif
</div>
@else
<div class="topbar">
  🚀 <b>FREE SHIPPING</b> on orders above ₹999 &nbsp;|&nbsp; Use <b>JANGO10</b> for 10% off &nbsp;|&nbsp; <a href="{{ route('products.index') }}">Shop Now ›</a>
</div>
@endif
