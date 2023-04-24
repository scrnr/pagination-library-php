<?php

declare(strict_types=1);

namespace Scrnr\Pagination;

class Pagination
{
    private string $arrowLinkClass;
    private string $dummyLinkClass;

    private string $liClass;
    private string $navClass;
    private string $ulClass;

    private string $activeClass;
    private string $linkClass;
    private string $nextClass;
    private string $prevClass;

    private string $id;

    private string $uri;
    private string $url;

    private bool $showArrowLinks;
    private bool $showDummyLinks;
    private bool $isGetPagination;

    private int $currentPage;
    private int $limitItems;
    private int $limitPages;
    private int $totalItems;
    private int $totalPages;

    private string $nextButton;
    private string $nextDummyButton;
    private string $prevButton;
    private string $prevDummyButton;

    private array $links;

    public function getPagination(?array $options = null): string
    {
        $this->options($options);
        $this->reset();

        if ($this->totalPages <= 1 or $this->limitPages < 3) {
            return '';
        }

        if ($this->currentPage > $this->totalPages) {
            $this->currentPage = $this->totalPages;
        } elseif ($this->currentPage <= 0) {
            $this->currentPage = 1;
        }

        $this->links = $this->getLinks();

        $pagination = $this->createPagination();

        return $pagination;
    }

    private function options(?array $options = null): void
    {
        if (is_array($options)) {
            extract($options);
        }

        $this->uri = $uri ?? '/';
        $this->url = $url ?? '';

        $this->arrowLinkClass = $arrowLinkClass ?? 'pagination__link arrow';
        $this->dummyLinkClass = $dummyLinkClass ?? 'pagination__link dummy';
        $this->liClass = $liClass ?? 'pagination__item';
        $this->linkClass = $linkClass ?? 'pagination__link';
        $this->navClass = $navClass ?? 'pagination';
        $this->ulClass = $ulClass ?? 'pagination__ul';
        $this->activeClass = $activeClass ?? 'pagination__link active';
        $this->nextClass = $nextClass ?? 'pagination__link next';
        $this->prevClass = $prevClass ?? 'pagination__link previous';
        $this->id = $id ?? 'pagination';

        $this->showArrowLinks = $showArrowLinks ?? true;
        $this->showDummyLinks = $this->showArrowLinks ? ($showDummyLinks ?? true) : false;
        $this->isGetPagination = $isGetPagination ?? false;

        $this->currentPage = $currentPage ?? 1;
        $this->limitItems = $limitItems ?? 15;
        $this->limitPages = $limitPages ?? 7;
        $this->totalItems = $totalItems ?? 65;
    }

    private function reset(): void
    {
        $this->nextButton = '';
        $this->nextDummyButton = '';
        $this->prevButton = '';
        $this->prevDummyButton = '';
        $this->links = [];
        $this->totalPages = $this->getCountPages();
    }

    private function getLinks(): array
    {
        if (!$this->checkLimitPages()) {
            return $this->links;
        }

        $limitPages = $this->limitPages - 1;
        $start = (int) ($this->currentPage - floor($limitPages / 2));
        $end = (int) ($this->currentPage + ceil($limitPages / 2));

        if ($start <= 0) {
            $end += abs($start) + 1;
            $start = 1;
        }

        if ($end > $this->totalPages) {
            $start -= ($end - $this->totalPages);
            $end = $this->totalPages;
        }

        if ($this->limitPages <= 4) {
            $this->showDummyLinks = false;
            $this->showArrowLinks = false;
        } elseif ($this->limitPages <= 6) {
            $this->showDummyLinks = false;
        }

        $this->createArrowLinks($start, $end);
        $this->createDummyLinks($start, $end);

        return range($start, $end);
    }

    private function createPagination(): string
    {
        $pages = [];
        $pagination = "<nav class='{$this->navClass}' id='{$this->id}'><ul class='{$this->ulClass}'>";
        $pages[] = $this->prevButton;
        $pages[] = $this->prevDummyButton;

        foreach ($this->links as $pageNumber) {
            $class = match ($pageNumber) {
                $this->currentPage => $this->activeClass,
                $this->currentPage - 1 => $this->prevClass,
                $this->currentPage + 1 => $this->nextClass,
                default => $this->linkClass
            };

            $url = $this->getUrl((string) $pageNumber);

            $pages[] = $this->getLink($url, (string) $pageNumber, $class);
        }

        $pages[] = $this->nextDummyButton;
        $pages[] = $this->nextButton;
        $pagination .= implode('', $pages);
        $pagination .= '</ul></nav>';

        return $pagination;
    }

    private function checkLimitPages(): bool
    {
        if ($this->totalPages <= $this->limitPages) {
            $this->links = range(1, $this->totalPages);

            return false;
        }

        if ($this->limitPages === 3) {
            match ($this->currentPage) {
                1 => $this->links = range(1, 3),
                $this->totalPages => $this->links = range($this->currentPage - 2, $this->currentPage),
                default => $this->links = range($this->currentPage - 1, $this->currentPage + 1)
            };

            return false;
        }

        return true;
    }

    private function createArrowLinks(int &$start, int &$end): void
    {
        if (!$this->showArrowLinks) {
            return;
        }

        if ($end !== $this->totalPages) {
            $end--;
            $nextUrl = $this->getUrl((string) $this->totalPages);
            $this->nextButton = $this->getLink($nextUrl, '&raquo', $this->arrowLinkClass);
        }

        if ($start > 1) {
            $start++;
            $prevUrl = $this->getUrl('1');
            $this->prevButton = $this->getLink($prevUrl, '&laquo', $this->arrowLinkClass);
        }
    }

    private function createDummyLinks(int &$start, int &$end): void
    {
        if (!$this->showDummyLinks) {
            return;
        }

        if ($end !== $this->totalPages - 1 and !empty($this->nextButton)) {
            $end--;
            $this->nextDummyButton = $this->getLink('', '...', $this->dummyLinkClass);
        }

        if ($start > 2 and !empty($this->prevButton)) {
            $start++;
            $this->prevDummyButton = $this->getLink('', '...', $this->dummyLinkClass);
        }
    }

    private function getUrl(string $page): string
    {
        $separator = $this->isGetPagination ? '=' : '/';
        $pattern = "# (?<page> p (age)? {$separator}) (\d+) #sx";

        $this->uri .= trim($this->uri) === '' ? '/' : '';
        $this->uri .= trim($this->uri) === '/' ? ($this->isGetPagination ? '?page=1' : 'page/1') : '';

        $uri = preg_replace_callback($pattern, fn (array $matches): string => $matches['page'] . $page, $this->uri);

        return $this->url . $uri;
    }

    private function getLink(string $url, string $text, string $class): string
    {
        $a = $this->getA($url, $text, $class);
        $link = $this->getLi($a);

        return $link;
    }

    private function getLi(string $a): string
    {
        return "<li class='{$this->liClass}'>{$a}</li>";
    }

    private function getA(string $url, string $text, string $class): string
    {
        return "<a href='{$url}' class='{$class}'>{$text}</a>";
    }

    private function getCountPages(): int
    {
        return (int) ceil($this->totalItems / $this->limitItems);
    }
}
