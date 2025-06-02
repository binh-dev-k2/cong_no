<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UserRequest;
use App\Models\User;
use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    private $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        $roles = $this->roleService->getAll();
        return view('user.index', compact('roles'));
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function userService()
    {
        return app(UserService::class);
    }

    public function register(UserRequest $request)
    {
        $data = $request->validated();
        $result = $this->userService()->create($data);

        return jsonResponse($result ? 0 : 1);
    }

    public function datatable(Request $request)
    {
        return $this->userService()->filterDatatable($request->all());
    }

    public function getAll()
    {
        $result = $this->userService()->getAll();
        return jsonResponse(0, $result);
    }

    public function delete(UserRequest $request)
    {
        $data = $request->validated();
        $result = $this->userService()->delete($data['id']);

        return jsonResponse($result ? 0 : 1);
    }

    public function updateRole(UserRequest $request)
    {
        $data = $request->validated();
        $result = $this->userService()->updateRole($data);

        return jsonResponse($result ? 0 : 1);
    }

    public function editPassword()
    {
        return view('auth.passwords.reset');
    }

    public function updatePassword(Request $request)
    {
        $request->validate($this->editPasswordRules(), $this->validationErrorMessages());
        $data = $request->all();
        $updated = $this->userService()->updatePasswrod($data);

        if ($updated) {
            Auth::logout();
            return redirect()->route('login');
        }

        return back()->withErrors(['current_password' => 'Mật khẩu bạn nhập không chính xác.']);
    }

    public function editPasswordRules()
    {
        return [
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ];
    }

    protected function validationErrorMessages()
    {
        return [
            'current_password.required' => 'Mật khẩu hiện tại là bắt buộc.',
            'password.required' => 'Mật khẩu mới là bắt buộc.',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp.',
            'password.min' => 'Mật khẩu mới phải có ít nhất :min ký tự.',
            'password.mixedCase' => 'Mật khẩu mới phải chứa cả chữ hoa và chữ thường.',
            'password.letters' => 'Mật khẩu mới phải chứa ít nhất một chữ cái.',
            'password.numbers' => 'Mật khẩu mới phải chứa ít nhất một số.',
            'password.symbols' => 'Mật khẩu mới phải chứa ít nhất một ký tự đặc biệt.',
            'password.uncompromised' => 'Mật khẩu mới đã bị lộ trong một vụ rò rỉ dữ liệu. Vui lòng chọn mật khẩu khác.',
        ];
    }
}
