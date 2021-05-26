<?php
defined('BASEPATH') or exit('No direct script access allowed');

// admin
define('_Website', 'tel:0398210215');
define('_nameWebsite', 'VH');
define('_nameWebsiteShort', 'VH');
define('_imgWebsiteDo', 'img/logo/logo-150-x-40-do.png');
define('_imgWebsite', 'img/logo/logo-120-x-32.png');
define('_imgWebsiteShort', 'img/logo/favicon-1.png');
define('_imgfavicon', 'img/logo/favicon-2.png');
// author
define('_author', 'Design by VIET HUYNH');
// email
define('_emailroot', 'icarecenter247@gmail.com');
define('_passemailroot', 'xsmjrsotqkjagcwc');
// url
define('_pg', 'pg'); // Trang
define('_csp', 'c-san-pham'); // Danh mục sản phẩm
define('_sp', 'san-pham'); // Sản phẩm
define('_cdv', 'c-dich-vu'); // Danh mục dịch vụ
define('_dv', 'dich-vu'); // Dịch vụ
define('_cim', 'c-hinh-anh'); // Danh mục hình ảnh
define('_im', 'hinh-anh'); // Hình ảnh
define('_tags', 'tags');
define('_tagsproduct', 'tags-sp');
define('_unit', 'đ');
// Hướng dẫn
define('_help_msp', 'Mã sản phẩm không được trùng nhau');
define('_help_catalog', 'Click chọn danh mục trong danh sách trên');
define('_help_capcoquan', 'Click chọn cấp cơ quan trong danh sách trên');
define('_help_coquanbanhanh', 'Click chọn cơ quan ban hành trong danh sách trên');
define('_help_catalog_parent', 'Click chọn danh mục CHA trong danh sách trên');
define('_help_name', 'Nhập tên. Tốt nhất là 60 ký tự');
define('_help_url', 'Link hiển thị. Không dấu và ký tự đặc biệt, khoảng trắng thay thế bằng dấu gạch ngang (-)');
define('_help_out_url', 'Mặc định bỏ trống. Khi bấm vào sản phẩm sẽ chuyển đến liên kết này. Ví dụ: <b>http://www.google.com</b>');
define('_help_price', 'Nếu không nhập giá thì sản phẩm sẽ hiện chữ Liên hệ');
define('_help_discount', 'Nếu không khuyến mãi thì để mặc định là 0');
define('_help_intro', 'Nhập mô tả ngắn');
define('_help_title_content', 'Tiêu đề cho nội dung bên dưới');
define('_help_content', 'Viết nội dung chính');
define('_help_img', 'Hình đại diện. Kích thước ');
define('_help_img_list', 'Chọn ảnh kèm theo. Kích thước ');
define('_help_sort', 'Thứ tự sẽ sắp xếp từ nhỏ đến lớn');
define('_help_timer', 'Chọn ngày xuất bản');
define('_help_title', 'Nội dung thẻ meta Title');
define('_help_description', 'Nội dung thẻ meta Description');
define('_help_keyword', 'Nội dung thẻ meta Keyword');
define('_help_link_doitac', 'Khi bấm vào hình đối tác sẽ chuyển đến liên kết này. Ví dụ: <b>http://www.google.com</b>');
define('_help_link_slide', 'Khi bấm vào hình slide sẽ chuyển đến liên kết này. Ví dụ: <b>http://www.google.com</b>');
define('_help_video', 'Lấy link video từ <b>Youtube</b>. Ví dụ: <b>https://www.youtube.com/watch?v=HUPB01vy5E4</b>');
define('_help_emailnhan', 'Gmail nhận thông tin khách hàng từ các <b>Form Liên hệ</b>');
define('_help_emailorder', 'Gmail nhận thông tin khách hàng từ các <b>Đơn hàng</b>');
define('_help_nganhang', 'Ngân hàng hiển thị trong trang thông tin thanh toán');
define('_help_script_head', 'Script hiển thị trong thẻ head');
define('_help_script_body', 'Script hiển thị đầu thẻ body');
define('_help_script_footer', 'Script hiển thị cuối thẻ body');

define('_catalog_parent_child', 1);
define('_catalognew_parent_child', 0);
define('_catalogservice_parent_child', 0);
define('_catalogimage_parent_child', 0);

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/

defined('SHOW_DEBUG_BACKTRACE') or define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  or define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') or define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   or define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  or define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           or define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     or define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       or define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  or define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   or define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              or define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            or define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       or define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        or define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          or define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         or define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   or define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  or define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') or define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     or define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       or define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      or define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      or define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
