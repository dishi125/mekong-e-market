<script !src="">
    $(document).ready(function () {
        $(document).on('click', '.status-change', function(event){
            var id=$(this).data('id');
            var result=confirm('Are You Sure Active.');
            if(result) {
                $.ajax({
                    url: '{{ url('statusChange') }}/' + id,
                    type: "POST",
                    data: {_token: '{{csrf_token()}}', action: action},
                    success: function (data) {
                        if (data.status == 0) {
                            alert(data.data);
                        }
                        fetch_data();
                    }
                });
            }
            else{
                fetch_data();
            }
        });

        $(document).on('click', '.status-change1', function(event){
            var id=$(this).data('id');
            var result=confirm('Are You Sure Deactive.');
            if(result) {
                $.ajax({
                    url: '{{ url('statusChange') }}/' + id,
                    type: "POST",
                    data: {_token: '{{csrf_token()}}', action: action},
                    success: function (data) {
                        if (data.status == 0) {
                            alert(data.data);
                        }
                        fetch_data();
                    }
                });
            }
            else{
                fetch_data();
            }
        });
    });
</script>
