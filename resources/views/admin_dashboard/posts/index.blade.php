@extends("admin_dashboard.layouts.app")
		
@section("wrapper")
<!--start page wrapper -->
<div class="page-wrapper">
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Bài viết</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Tất cả bài viết</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
     
        <div class="card">
            <div class="card-body">
                <div class="d-lg-flex align-items-center mb-4 gap-3">
                    <div class="position-relative">
                        <input type="text" class="form-control ps-5 radius-30" placeholder="Tìm kiếm bài viết"> <span class="position-absolute top-50 product-show translate-middle-y"><i class="bx bx-search"></i></span>
                    </div>
                    <div class="ms-auto"><a href="{{ route('admin.posts.create') }}" class="btn btn-primary radius-30 mt-2 mt-lg-0"><i class="bx bxs-plus-square"></i>Thêm bài viết mới</a></div>
                    <button id="delete-selected" class="btn btn-danger">Xóa tất cả đã chọn</button>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Mã bài viết</th>
                                <th>Tên bài viết</th>
                                <th>Mô tả</th>
                                <th>Danh mục</th>
                                <th>Ngày tạo</th>
                                <th>Trạng thái</th>
                                <th>Lượt xem</th>
                                <th>Chức năng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <input type="checkbox" name="selected_posts[]" value="{{ $post->id }}" class="post-checkbox">
                                        </div>
                                        <div class="ms-2">
                                            <h6 class="mb-0 font-14">#P-{{ $post->id }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $post->title }}</td>
                               
                                
                                <td>{{ $post->excerpt }}</td>
                                <td>{{ $post->category->name }}</td>
                                <td>{{ $post->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="badge rounded-pill @if($post->approved === 1)  {{'text-success bg-light-success' }} @else {{'text-danger bg-light-danger' }} @endif p-2 text-uppercase px-3">
                                        <i class='bx bxs-circle me-1'></i>{{ $post->approved  === 1 ? 'Đã phê duyệt' : 'Chưa phê duyệt'  }}
                                    </div>
                                </td>
                                <td>{{ $post->views }}</td>
                               
                                <td>
                                    <div class="d-flex order-actions">
                                        <a href="{{ route('admin.posts.edit', $post)}}" class=""><i class='bx bxs-edit'></i></a>
                                        <a href="#" onclick="event.preventDefault(); document.querySelector('#delete_form_{{ $post->id }}').submit();" class="ms-3"><i class='bx bxs-trash'></i></a>

                                        <form method="post" action="{{ route('admin.posts.destroy', $post) }}" id="delete_form_{{ $post->id }}">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                          
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">{{ $posts->links() }}</div>

            </div>
        </div>


    </div>
</div>
<!--end page wrapper -->
@endsection

@section("script")
	<script>
		$(document).ready(function () {
		setTimeout(()=>{
				$(".general-message").fadeOut();
		},5000);

		});
	</script>

    <script>
        $(document).ready(function () {
            // Chọn/bỏ chọn tất cả
            $('#select-all').change(function () {
                $('.post-checkbox').prop('checked', this.checked);
            });

            // Xóa các bài viết đã chọn
            $('#delete-selected').click(function () {
                var selectedPosts = $('input[name="selected_posts[]"]:checked').map(function () {
                    return this.value;
                }).get();

                if (selectedPosts.length > 0) {
                    if (confirm('Bạn có chắc chắn muốn xóa các bài viết đã chọn?')) {
                        $.ajax({
                            url: '/admin/posts/delete-selected', // URL để xóa
                            type: 'DELETE',
                            data: {
                                ids: selectedPosts,
                                _token: '{{ csrf_token() }}'
                            },
                            success: function (response) {
                                // Xử lý thành công (ví dụ: tải lại trang hoặc xóa các hàng đã chọn)
                                alert(response.message);
                                location.reload();
                            },
                            error: function (error) {
                                // Xử lý lỗi
                                alert('Đã có lỗi xảy ra.');
                            }
                        });
                    }
                } else {
                    alert('Vui lòng chọn các bài viết cần xóa.');
                }
            });
        });
    </script>



@endsection
