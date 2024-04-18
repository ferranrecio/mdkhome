<div class="d-flex gap-3 border-bottom mb-1" id="instanceFilters">
  <div class="form-check ps-2">
    <input class="form-check-input" type="radio" name="filter" id="all" value="" checked>
    <label class="form-check-label" for="all">All</label>
  </div>
  <div class="form-check ps-2">
    <input class="form-check-input" type="radio" name="filter" id="MDL" value="MDL">
    <label class="form-check-label" for="MDL">MDL</label>
  </div>
  <div class="form-check ps-2">
    <input class="form-check-input" type="radio" name="filter" id="int" value="int">
    <label class="form-check-label" for="int">Integration</label>
  </div>
  <div class="form-check ps-2">
    <input class="form-check-input" type="radio" name="filter" id="stable" value="stable">
    <label class="form-check-label" for="stable">Stable</label>
  </div>
  <div class="form-check ps-2">
    <input class="form-check-input" type="radio" name="filter" id="other" value="other">
    <label class="form-check-label" for="other">Other</label>
  </div>
</div>
<script src="<?php out::url('js/filter.js'); ?>"></script>
