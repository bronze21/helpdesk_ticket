<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $upload_by
 * @property string $path
 * @property string $name
 * @property string $type
 * @property float $size
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\User $uploader
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment query()
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attachment whereUploadBy($value)
 */
	class Attachment extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $isActive
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subcategory> $subCategories
 * @property-read int|null $sub_categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ticket> $tickets
 * @property-read int|null $tickets_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Category withoutTrashed()
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property int $isActive
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Role withoutTrashed()
 */
	class Role extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $role_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Role $role
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|RoleUser whereUserId($value)
 */
	class RoleUser extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $category_id
 * @property string $name
 * @property string $slug
 * @property int $isActive
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ticket> $tickets
 * @property-read int|null $tickets_count
 * @method static \Illuminate\Database\Eloquent\Builder|Subcategory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subcategory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subcategory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Subcategory query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subcategory whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subcategory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subcategory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subcategory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subcategory whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subcategory whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subcategory whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subcategory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subcategory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Subcategory withoutTrashed()
 */
	class Subcategory extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $category_id
 * @property int|null $subcategory_id
 * @property string $title
 * @property string $code
 * @property string $priority low, normal, high
 * @property string $status open | on_going | resolved | unresolved | closed
 * @property \Illuminate\Support\Carbon $due_date
 * @property int $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\User|null $activeAgent
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $agents
 * @property-read int|null $agents_count
 * @property-read \App\Models\Category $category
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketsComment> $comments
 * @property-read int|null $comments_count
 * @property-read mixed $available_status
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketsHistory> $histories
 * @property-read int|null $histories_count
 * @property-read \App\Models\User $owner
 * @property-read \App\Models\Subcategory|null $subcategory
 * @property-read mixed $t_options
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket latestUpdate()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket query()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereSubcategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Ticket withoutTrashed()
 */
	class Ticket extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property int $ticket_id
 * @property int $ticket_comment_id
 * @property int $attachment_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Attachment $attachment
 * @property-read \App\Models\Ticket $ticket
 * @property-read \App\Models\TicketsComment $ticket_comment
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|TicketAttachments newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketAttachments newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketAttachments query()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketAttachments whereAttachmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketAttachments whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketAttachments whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketAttachments whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketAttachments whereTicketCommentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketAttachments whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketAttachments whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketAttachments whereUserId($value)
 */
	class TicketAttachments extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $ticket_id
 * @property int $user_id
 * @property int $isActive
 * @property \Illuminate\Support\Carbon $due_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Ticket $ticket
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsAgent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsAgent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsAgent query()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsAgent whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsAgent whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsAgent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsAgent whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsAgent whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsAgent whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsAgent whereUserId($value)
 */
	class TicketsAgent extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $user_id
 * @property string $user_type
 * @property int $ticket_id
 * @property string $messages
 * @property int $isRead
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attachment> $attachments
 * @property-read int|null $attachments_count
 * @property-read \App\Models\Ticket $ticket
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsComment onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsComment query()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsComment whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsComment whereIsRead($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsComment whereMessages($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsComment whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsComment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsComment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsComment whereUserType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsComment withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsComment withoutTrashed()
 */
	class TicketsComment extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int|null $user_id
 * @property int $ticket_id
 * @property string $action create | update | read | comment | delete
 * @property string $description
 * @property object|null $old_data
 * @property object|null $new_data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \App\Models\Ticket $ticket
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsHistory newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsHistory newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsHistory onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsHistory query()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsHistory whereAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsHistory whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsHistory whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsHistory whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsHistory whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsHistory whereNewData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsHistory whereOldData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsHistory whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsHistory whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsHistory whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsHistory withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|TicketsHistory withoutTrashed()
 */
	class TicketsHistory extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $phone_number
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TicketsComment> $comments
 * @property-read int|null $comments_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Role|null $role
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ticket> $tickets
 * @property-read int|null $tickets_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User queryRole($role)
 * @method static \Illuminate\Database\Eloquent\Builder|User queryWithoutRole(?array $notRole = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutTrashed()
 */
	class User extends \Eloquent {}
}

