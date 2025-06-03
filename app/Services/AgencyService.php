<?php

namespace App\Services;

use App\Models\Agency;
use App\Models\AgencyBusiness;
use App\Models\Machine;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class AgencyService
{
    /**
     * Get agency statistics for dashboard.
     */
    public function getAgencyStatistics()
    {
        $agencyQuery = Agency::where('owner_id', auth()->user()->id);
        $totalAgencies = $agencyQuery->count();
        $totalBusinesses = AgencyBusiness::whereIn('agency_id', $agencyQuery->pluck('id'))->count();
        $completedBusinesses = AgencyBusiness::whereIn('agency_id', $agencyQuery->pluck('id'))->where('is_completed', true)->count();
        $pendingBusinesses = AgencyBusiness::whereIn('agency_id', $agencyQuery->pluck('id'))->where('is_completed', false)->count();

        return [
            'total_agencies' => $totalAgencies,
            'total_businesses' => $totalBusinesses,
            'completed_businesses' => $completedBusinesses,
            'pending_businesses' => $pendingBusinesses
        ];
    }

    /**
     * Create a new agency.
     */
    public function createAgency(array $data)
    {
        return DB::transaction(function () use ($data) {
            $agency = Agency::create([
                'name' => $data['name'],
                'fee_percent' => $data['fee_percent'],
                'machine_fee_percent' => $data['machine_fee_percent'],
                'owner_id' => auth()->user()->id
            ]);

            // Attach machines if provided
            if (!empty($data['machines'])) {
                $agency->machines()->attach($data['machines']);
            }

            if (!empty($data['users'])) {
                $agency->users()->attach($data['users']);
            }

            return $agency;
        });
    }

    /**
     * Update an existing agency.
     */
    public function updateAgency($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $agency = Agency::findOrFail($id);
            $agency->update([
                'name' => $data['name'],
                'fee_percent' => $data['fee_percent'],
                'machine_fee_percent' => $data['machine_fee_percent']
            ]);

            // Sync machines
            $agency->machines()->sync($data['machines'] ?? []);

            // Sync users
            $agency->users()->sync($data['users'] ?? []);

            return $agency;
        });
    }

    /**
     * Delete an agency.
     */
    public function deleteAgency($id)
    {
        return DB::transaction(function () use ($id) {
            $agency = Agency::findOrFail($id);

            // Business rule: Check if agency has businesses
            if ($agency->agencyBusinesses()->count() > 0) {
                throw new \InvalidArgumentException('Không thể xóa đại lý đang có nghiệp vụ');
            }

            $agency->delete();
            return true;
        });
    }

    /**
     * Get businesses for a specific agency.
     */
    public function getAgencyBusinesses($agencyId)
    {
        return AgencyBusiness::with(['machine', 'agency'])
            ->where('agency_id', $agencyId)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get agency business details by ID.
     */
    public function getAgencyBusinessById($businessId)
    {
        return AgencyBusiness::with(['machine', 'agency'])
            ->findOrFail($businessId);
    }

    /**
     * Create a new agency business.
     */
    public function createAgencyBusiness(array $data)
    {
        return DB::transaction(function () use ($data) {
            $business = AgencyBusiness::create([
                'agency_id' => $data['agency_id'],
                'machine_id' => $data['machine_id'],
                'total_money' => $data['total_money'],
                'standard_code' => $data['standard_code'] ?? null,
                'is_completed' => false,
                'image_front' => null,
                'image_summary' => null
            ]);

            $business->load(['machine', 'agency']);
            return $business;
        });
    }

    /**
     * Update an agency business.
     */
    public function updateAgencyBusiness($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $business = AgencyBusiness::findOrFail($id);

            // Prepare update data
            $updateData = [
                'machine_id' => $data['machine_id'],
                'total_money' => $data['total_money'],
                'standard_code' => $data['standard_code'] ?? null,
                'is_completed' => $data['is_completed'] ?? $business->is_completed
            ];

            // Handle front image upload
            if (isset($data['image_front']) && $data['image_front']) {
                // Delete old image if exists
                if ($business->image_front && Storage::disk('public')->exists($business->image_front)) {
                    Storage::disk('public')->delete($business->image_front);
                }
                $fileName = 'front_' . time() . '_' . $data['image_front']->getClientOriginalName();
                $updateData['image_front'] = $data['image_front']->storeAs('agency_business', $fileName, 'public');
            }

            // Handle summary image upload
            if (isset($data['image_summary']) && $data['image_summary']) {
                // Delete old image if exists
                if ($business->image_summary && Storage::disk('public')->exists($business->image_summary)) {
                    Storage::disk('public')->delete($business->image_summary);
                }
                $fileName = 'summary_' . time() . '_' . $data['image_summary']->getClientOriginalName();
                $updateData['image_summary'] = $data['image_summary']->storeAs('agency_business', $fileName, 'public');
            }

            $business->update($updateData);
            $business->load(['machine', 'agency']);
            return $business;
        });
    }

    /**
     * Delete an agency business.
     */
    public function deleteAgencyBusiness($id)
    {
        return DB::transaction(function () use ($id) {
            $business = AgencyBusiness::findOrFail($id);
            if ($business->image_front && Storage::disk('public')->exists($business->image_front)) {
                Storage::disk('public')->delete($business->image_front);
            }
            if ($business->image_summary && Storage::disk('public')->exists($business->image_summary)) {
                Storage::disk('public')->delete($business->image_summary);
            }
            $business->delete();
            return true;
        });
    }

    /**
     * Complete an agency business.
     */
    public function completeAgencyBusiness($id)
    {
        return DB::transaction(function () use ($id) {
            $agencyBusiness = AgencyBusiness::findOrFail($id);

            // Check if already completed
            if ($agencyBusiness->is_completed) {
                throw new \InvalidArgumentException('Nghiệp vụ này đã được hoàn thành rồi');
            }

            $agency = $agencyBusiness->agency;
            $profit = (float)($agencyBusiness->total_money * ($agency->fee_percent - $agency->machine_fee_percent) / 100);
            $amountToPay = (float)$agencyBusiness->total_money - ($agencyBusiness->total_money * $agency->fee_percent / 100);
            return $agencyBusiness->update(
                [
                    'is_completed' => true,
                    'profit' => $profit,
                    'amount_to_pay' => $amountToPay
                ]
            );
        });
    }

    /**
     * Get all machines for dropdown.
     */
    public function getAllMachines()
    {
        return Machine::orderBy('name')
            ->get(['id', 'name']);
    }

    /**
     * Check if agency can be deleted.
     */
    public function canDeleteAgency($id)
    {
        $agency = Agency::findOrFail($id);
        return $agency->agencyBusinesses()->count() === 0;
    }

    /**
     * Get agencies with business statistics.
     */
    public function getAgenciesWithStats()
    {
        return Agency::withCount([
            'agencyBusinesses',
            'agencyBusinesses as completed_businesses_count' => function ($query) {
                $query->where('is_completed', true);
            },
            'agencyBusinesses as pending_businesses_count' => function ($query) {
                $query->where('is_completed', false);
            }
        ])
            ->with(['agencyBusinesses' => function ($query) {
                $query->selectRaw('agency_id, SUM(total_money) as total_amount')
                    ->groupBy('agency_id');
            }])
            ->orderBy('name')
            ->get();
    }

    /**
     * Get machines for specific agency (for business modal).
     */
    public function getMachinesForAgency($agencyId)
    {
        $agency = Agency::findOrFail($agencyId);
        return $agency->agencyMachines()->with('machine')->get()->map(function ($agencyMachine) {
            return [
                'id' => $agencyMachine->machine->id,
                'name' => $agencyMachine->machine->name,
                'fee' => $agencyMachine->machine->fee ?? null,
            ];
        });
    }

    /**
     * Get agencies based on user permissions
     */
    public function getAgenciesForUser($search = null)
    {
        $user = auth()->user();

        // If user is owner, return all agencies
        if (Agency::where('owner_id', $user->id)->exists()) {
            return Agency::query()
                ->where('owner_id', $user->id)
                ->when($search, function ($query, $search) {
                    $query->where('name', 'LIKE', "%{$search}%");
                })
                ->withCount(['agencyBusinesses' => function ($query) {
                    $query->where('is_completed', false);
                }])
                ->withCount('agencyUsers')
                ->with([
                    'agencyMachines.machine',
                    'agencyBusinesses' => function ($query) {
                        $query->where('is_completed', false)
                            ->with('machine');
                    }
                ])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // For other users, return only assigned agencies
        $columns = Schema::getColumnListing('agencies');
        $columns = array_diff($columns, ['machine_fee_percent']); // loại bỏ cột 'machine_fee_percent'
        return Agency::query()
            ->select($columns)
            ->whereHas('agencyUsers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->withCount(['agencyBusinesses' => function ($query) {
                $query->where('is_completed', false);
            }])
            ->with([
                'agencyMachines.machine',
                'agencyBusinesses' => function ($query) {
                    $query->where('is_completed', false)
                        ->with('machine');
                }
            ])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Check if user can manage agency business
     */
    public function canManageAgencyBusiness($agencyId = null, $businessId = null)
    {
        $user = auth()->user();

        if ($businessId) {
            $business = AgencyBusiness::findOrFail($businessId);
            $agencyId = $business->agency_id;
        }

        if (Agency::where('id', $agencyId)->where('owner_id', $user->id)->exists()) {
            return true;
        }

        // Check if user is assigned to this agency
        return Agency::where('id', $agencyId)
            ->whereHas('agencyUsers', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->exists();
    }

    /**
     * Get agency statistics based on user permissions
     */
    public function getAgencyStatisticsForUser()
    {
        $user = auth()->user();

        // If user is owner, return all statistics
        if (Agency::where('owner_id', $user->id)->exists()) {
            return $this->getAgencyStatistics();
        }

        // For other users, return statistics only for assigned agencies
        $agencyIds = $user->agencyUsers()->pluck('agency_id');

        $totalAgencies = Agency::whereIn('id', $agencyIds)->count();
        $totalBusinesses = AgencyBusiness::whereIn('agency_id', $agencyIds)->count();
        $completedBusinesses = AgencyBusiness::whereIn('agency_id', $agencyIds)
            ->where('is_completed', true)
            ->count();
        $pendingBusinesses = AgencyBusiness::whereIn('agency_id', $agencyIds)
            ->where('is_completed', false)
            ->count();

        return [
            'total_agencies' => $totalAgencies,
            'total_businesses' => $totalBusinesses,
            'completed_businesses' => $completedBusinesses,
            'pending_businesses' => $pendingBusinesses
        ];
    }

    public function getAgencyAnalyticsDatatable($request)
    {
        $user = auth()->user();

        $query = Agency::query()
            ->where('owner_id', $user->id)
            ->with([
                'agencyBusinesses' => fn($q) => $q->where('is_completed', true),
            ])
            ->withCount([
                'agencyBusinesses as completed_businesses_count' => fn($q) => $q->where('is_completed', true),
                'agencyBusinesses as uncompleted_businesses_count' => fn($q) => $q->where('is_completed', false),
            ])
            ->withSum([
                'agencyBusinesses' => fn($q) => $q->where('is_completed', true),
            ], 'total_money')
            ->withSum([
                'agencyBusinesses' => fn($q) => $q->where('is_completed', true),
            ], 'profit');

        if ($request->filled('agency_id')) {
            $query->where('id', $request->agency_id);
        }

        if ($request->filled('date_from') || $request->filled('date_to')) {
            $query->whereHas('agencyBusinesses', function ($q) use ($request) {
                if ($request->filled('date_from')) {
                    $q->whereDate('updated_at', '>=', $request->date_from);
                }
                if ($request->filled('date_to')) {
                    $q->whereDate('updated_at', '<=', $request->date_to);
                }
            });
        }

        $totalRecords = $query->count(); // Nếu cần recordsTotal cho toàn bộ data không filter thì cần clone() trước filter
        $filteredRecords = $totalRecords;

        $queryClone = clone $query;

        $agencies = $query
            ->skip((int) $request->input('start', 0))
            ->take((int) $request->input('length', 10))
            ->get();

        return [
            'draw' => (int) $request->input('draw', 1),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $agencies,
            'total_profit' => $queryClone->get()->sum('agency_businesses_sum_profit'),
            'total_money' => $queryClone->get()->sum('agency_businesses_sum_total_money')
        ];
    }


    /**
     * Get completed businesses for datatable based on user permissions
     */
    public function getCompletedBusinessesDatatable($request)
    {
        $user = auth()->user();
        $query = AgencyBusiness::with(['agency', 'machine'])
            ->where('is_completed', true);

        // If not owner of any agency, filter by assigned agencies
        if (!Agency::where('owner_id', $user->id)->exists()) {
            $agencyIds = $user->agencyUsers()->pluck('agency_id');
            $query->whereIn('agency_id', $agencyIds);
        }

        // Apply filters
        if ($request->filled('agency_id')) {
            $query->where('agency_id', $request->agency_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('updated_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('updated_at', '<=', $request->date_to);
        }

        // Search functionality
        if ($request->filled('search.value')) {
            $search = $request->input('search.value');
            $query->where(function ($q) use ($search) {
                $q->whereHas('agency', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%");
                })
                    ->orWhereHas('machine', function ($q) use ($search) {
                        $q->where('name', 'LIKE', "%{$search}%");
                    })
                    ->orWhere('standard_code', 'LIKE', "%{$search}%");
            });
        }

        // Get total records
        $totalRecords = $query->count();
        $filteredRecords = $query->count();

        // Apply ordering
        if ($request->filled('order')) {
            $orderColumn = 'updated_at';
            $orderDir = 'desc';

            $columns = [
                0 => 'id',
                1 => 'agency.name',
                2 => 'machine.name',
                3 => 'total_money',
                4 => 'standard_code',
                5 => 'total_money',
                6 => 'updated_at',
                7 => 'id'
            ];

            if (isset($columns[$orderColumn])) {
                if ($columns[$orderColumn] == 'agency.name') {
                    $query->join('agencies', 'agency_businesses.agency_id', '=', 'agencies.id')
                        ->orderBy('agencies.name', $orderDir)
                        ->select('agency_businesses.*');
                } elseif ($columns[$orderColumn] == 'machine.name') {
                    $query->join('machines', 'agency_businesses.machine_id', '=', 'machines.id')
                        ->orderBy('machines.name', $orderDir)
                        ->select('agency_businesses.*');
                } else {
                    $query->orderBy($columns[$orderColumn], $orderDir);
                }
            }
        } else {
            $query->orderBy('updated_at', 'desc');
        }

        // Apply pagination
        if ($request->filled('start') && $request->filled('length')) {
            $query->skip($request->start)->take($request->length);
        }

        $data = $query->get();

        return [
            'draw' => intval($request->input('draw')),
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data
        ];
    }
}
