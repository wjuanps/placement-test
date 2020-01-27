    
<input type="hidden" id="placement_key" value="<?= $placement_key ?>" />

<script>
    window.onload = () => {
        let placementKey = document.getElementById('placement_key');

        localStorage.setItem('placement', placementKey.value);

        location.pathname = '/test-your-english';
    };
</script>
