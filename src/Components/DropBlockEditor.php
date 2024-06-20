<?php

namespace App\Livewire\DropBlockEditor;

use App\Classes\Block ;
use App\Models\pages;
use Illuminate\Support\Str;
use Livewire\Component;
use Mo_sweed\DropBlockEditor\Parsers\Parse;
use Illuminate\Filesystem\Filesystem;
use SplFileInfo;
use ReflectionClass;
use Illuminate\Validation\Rule;

class DropBlockEditor extends Component
{
    public $initialRender = true;

    public $title;

    public $base = 'dropblockeditor::base';

    public $hash;

    public $parsers = [];

    public $result;

    public $activeBlockIndex = false;

    public $activeBlocks = [];

    public $history = [];

    public int $historyIndex = -1;



    public $blocks = null;

    public $slug ;

    public $status;

    public $page;

    public $selctedTab= 'blocks';

    protected $listeners = [
        'blockEditComponentSelected' => 'blockSelected',
        'blockEditComponentUpdated' => 'blockUpdated',
        'refreshComponent' => '$refresh',
        'url_updated' => 'url_updated',
    ];


    public function save()
    {

         $this->selctedTab= 'settings';


        if($this->page){

            $this->validate([
                'title' =>  [ 'required', 'min:3' , Rule::unique(pages::class)->ignore($this->page->id)],
                'slug' =>  [ 'required', 'min:3' , Rule::unique(pages::class)->ignore($this->page->id)],
                'status' => 'required| in:published,draft',
            ]);

            $this->page->update([
                'title' => $this->title,
                'slug' => $this->slug,
                'status' => $this->status,
                'content' => json_encode($this->activeBlocks),
            ]);
        }else{
            $this->validate([
                'title' => 'required | min:3 | unique:pages,title',
                'slug' => 'required | min:3 | unique:pages,slug',
                'status' => 'required| in:published,draft',
            ]);


            $this->page =  pages::create([
                'title' => $this->title,
                'slug' => $this->slug,
                'status' => $this->status,
                'content' => json_encode($this->activeBlocks),
            ]);

        }


        $this->dispatch('swal_save');

      //  dd( $this->page);
        // $activeBlocks = collect($this->properties['activeBlocks'])
        // ->toJson();
        //  $this->dispatch('savepage', $activeBlocks);
    }

    public function url_updated()
    {
       return redirect()->route('pages.edit', $this->page->slug);
    }


    public function updatedTitle($value)
    {
        $this->slug = \Str::slug($value);
    }

    public function changeTab($tab)
    {
        $this->selctedTab = $tab;
    }

    public function canUndo(): bool
    {
        return $this->historyIndex > 0;
    }

    public function canRedo(): bool
    {
        return $this->historyIndex < count($this->history) - 1;
    }

    public function undo(): void
    {
        if (! $this->canUndo()) {
            return;
        }

        $this->historyIndex--;

        $this->activeBlocks = $this->history[$this->historyIndex]['activeBlocks'];
        $this->activeBlockIndex = $this->history[$this->historyIndex]['activeBlockIndex'];
        $this->updateHash();
    }

    public function updateHash(): void
    {
        $this->hash = Str::random(10);
    }

    public function redo(): void
    {
        if (! $this->canRedo()) {
            return;
        }

        $this->historyIndex++;

        $this->activeBlocks = $this->history[$this->historyIndex]['activeBlocks'];
        $this->activeBlockIndex = $this->history[$this->historyIndex]['activeBlockIndex'];
        $this->updateHash();
    }

    public function recordInHistory(): void
    {
        $history = collect($this->history)
            ->slice(0, $this->historyIndex + 1)
            ->push([
                'activeBlocks' => $this->activeBlocks,
                'activeBlockIndex' => $this->activeBlockIndex,
            ])
            ->take(-5)
            ->values();

        $this->history = $history->toArray();

        $this->historyIndex = count($this->history) - 1;
    }

    public function blockUpdated($position, $data): void
    {
        $this->activeBlocks[$position]['data'] = $data;

        $this->recordInHistory();
    }

    public function blockSelected($blockId): void
    {
        $this->activeBlockIndex = $blockId;

        $this->selctedTab= 'blocks';


        $this->recordInHistory();
    }

    public function cloneBlock(): void
    {
        $clone = $this->activeBlocks[$this->activeBlockIndex];

        $this->activeBlocks[] = $clone;

        $this->activeBlockIndex = array_key_last($this->activeBlocks);

        $this->recordInHistory();
    }

    public function deleteBlock(): void
    {
        $activeBlockId = $this->activeBlockIndex;

        $this->activeBlockIndex = false;

        unset($this->activeBlocks[$activeBlockId]);

        $this->recordInHistory();
    }

    public function getBlockFromClassName($name): Block
    {
        return Block::fromName($name);
    }

    public function getActiveBlock(): bool|Block
    {
        if (isset($this->activeBlockIndex) && $this->activeBlockIndex === false) {
            return false;
        }

        return Block::fromName($this->activeBlocks[$this->activeBlockIndex]['class'])
            ->data($this->activeBlocks[$this->activeBlockIndex]['data']);
    }




    public $shazzoo_components = [];



    protected function registerComponentsFromDirectory($baseClass, $register, $directory, $namespace)
    {

        if (blank($directory) || blank($namespace)) {
            return;
        }

        $filesystem = app(Filesystem::class);

        if ((! $filesystem->exists($directory)) && (! Str::of($directory)->contains(''))) {
            return;
        }

        $namespace = Str::of($namespace);
        $register = array_merge(
            $register,
            collect($filesystem->allFiles($directory))
                ->map(function (SplFileInfo $file) use ($namespace): string {
                    $variableNamespace = $namespace->contains('') ? str_ireplace(
                        [$namespace->beforeLast('\\'), $namespace->afterLast('\\')],
                        ['', ''],
                        Str::of($file->getPath())
                            ->after(base_path())
                            ->replace(['/', '\\'])
                    ) : null;
                    if (is_string($variableNamespace)) {
                        $variableNamespace = (string) Str::of($variableNamespace)->before('\\');
                    }

                    return (string) $namespace
                        ->append('\\', $file->getRelativePathname())
                        ->replace('*', $variableNamespace)
                        ->replace(['/', '.php'], ['\\', '']);
                })
                ->filter(fn (string $class): bool => is_subclass_of($class, $baseClass) && (! (new ReflectionClass($class))->isAbstract()))
                ->all(),
        );

        $test = [];
        foreach ($register as $class) {
            // Instantiate the class
            $instance = app($class);

            // Call the desired function on the class instance
          $test[] =  $instance->toArray(); // Replace yourFunction() with the actual function you want to call

           // dd($register);
        }


       // get all classes in a directory and register them in the $register array



        return $test;
    }


    public function reorder($ids): void
    {
        $this->activeBlocks = collect($ids)
            ->map(function ($id) {
                return $this->activeBlocks[$id];
            })
            ->all();

        //$this->dispatch('editorIsUpdated', $this->updateProperties());
    }

    public function insertBlock($id, $index = null, $placement = null): void
    {
        if ($index === null) {
            $block = $this->blocks[$id];

            $this->activeBlocks[] = $block;

            return;
        }

        if ($placement === 'before') {
            $newIndex = $index - 1 == -1 ? 0 : $index - 1;
        } else {
            $newIndex = $index + 1;
        }

        $this->activeBlocks = array_merge(array_slice($this->activeBlocks, 0, $newIndex), [$this->blocks[$id]], array_slice($this->activeBlocks, $newIndex));

        $this->recordInHistory();
    }

    public function prepareActiveBlockKey($activeBlockIndex): string
    {
        return "{$activeBlockIndex}-{$this->hash}";
    }

    public function updateProperties(): array
    {
        return [
            'base' => $this->base,
            'parsers' => $this->parsers,
            'activeBlocks' => $this->activeBlocks,
        ];
    }

    public function process(): void
    {
        $this->result = Parse::execute([
            'activeBlocks' => $this->activeBlocks,
            'base' => $this->base,
            'context' => 'editor',
            'parsers' => $this->parsers,
        ]);
    }

    public function mount(): void
    {
        $this->parsers = config('dropblockeditor.parsers', []);

        $this->slug = \Str::slug( $this->title);


        $this->blocks =    $this->registerComponentsFromDirectory(
            Block::class,
            $this->shazzoo_components,
            app_path('Classes\Blocks'),
            'App\Classes\Blocks',
        );


        if($this->page){
            $this->title = $this->page->title;
            $this->slug = $this->page->slug;
            $this->status = $this->page->status;
            $this->activeBlocks = json_decode($this->page->content, true);
        }


        // $this->blocks = collect(! is_null($this->blocks) ? $this->blocks : config('dropblockeditor.blocks', []))
        //     ->map(fn ($block) => (new $block)->toArray())
        //     ->all();
       // dd($this->blocks);

        $this->updateHash();

        $this->recordInHistory();
    }

    public function render()
    {
        $this->process();






        $this->initialRender = false;

        return view('dropblockeditor::editor', [
            'activeBlock' => $this->getActiveBlock(),
        ]);
    }
}